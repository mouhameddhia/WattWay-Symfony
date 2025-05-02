<?php

namespace App\Controller\Order;

use App\Entity\Item;
use App\Form\CreateItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ItemsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Create a new Item with optional image upload.
     */
    #[Route('/items/create', name: 'create_item')]
    public function create(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(CreateItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $fileName = $item->getNameItem() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('images_directory'), $fileName);
            }

            $item->setQuantityItem(0);
            $this->entityManager->persist($item);
            $this->entityManager->flush();

            return $this->redirectToRoute('items');
        }

        return $this->render('backend/order/createItem.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit an existing item, handle image replacement or renaming.
     */
    #[Route('/item/{id}/edit', name: 'edit_item', methods: ['GET', 'POST'])]
    public function editItem(Request $request, Item $item, EntityManagerInterface $em, LoggerInterface $logger): Response
    {
        $form = $this->createForm(CreateItemType::class, $item, [
            'action' => $this->generateUrl('edit_item', ['id' => $item->getIdItem()])
        ]);
        $form->handleRequest($request);
        $oldName = $item->getNameItem();
        $filesystem = new Filesystem();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $uploadedImage */
            $uploadedImage = $form->get('image')->getData();

            if ($uploadedImage) {
                $imageName = $item->getNameItem() . '.png';
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/images';
                $oldImagePath = $uploadDir . '/' . $oldName . '.png';

                if ($filesystem->exists($oldImagePath)) {
                    $filesystem->remove($oldImagePath);
                    $logger->info('Old image removed', ['path' => $oldImagePath]);
                }

                $uploadedImage->move($uploadDir, $imageName);
                $logger->info('New image saved', ['path' => $uploadDir . '/' . $imageName]);
            } elseif ($oldName !== $item->getNameItem()) {
                $oldPath = $this->getParameter('kernel.project_dir') . '/public/images/' . $oldName . '.png';
                $newPath = $this->getParameter('kernel.project_dir') . '/public/images/' . $item->getNameItem() . '.png';

                if ($filesystem->exists($oldPath)) {
                    $filesystem->rename($oldPath, $newPath, true);
                    $logger->info('Image renamed', ['old' => $oldPath, 'new' => $newPath]);
                }
            }

            $em->flush();
            $logger->info('Item updated', ['id' => $item->getIdItem()]);
            return $this->redirectToRoute('items');
        }

        return $this->render('backend/order/editItem.html.twig', [
            'form' => $form->createView(),
            'originalNameItem' => $oldName,
        ]);
    }

    /**
     * Delete an item by ID.
     */
    #[Route('/items/delete/{idItem}', name: 'item_delete', methods: ['GET'])]
    public function deleteItem(int $idItem, EntityManagerInterface $entityManager): Response
    {
        $item = $entityManager->getRepository(Item::class)->find($idItem);

        if (!$item) {
            $this->addFlash('error', 'Item not found!');
            return $this->redirectToRoute('items');
        }

        $entityManager->remove($item);
        $entityManager->flush();

        $this->addFlash('success', 'Item deleted successfully!');
        return $this->redirectToRoute('items');
    }

    /**
     * AI-based suggestion for price and category.
     */
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/ai/suggest-price', name: 'ai_suggest_price', methods: ['POST'])]
    public function suggestPrice(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $itemName = $data['itemName'] ?? '';

        if (!$itemName) {
            return $this->json(['error' => 'Missing item name'], 400);
        }

        $client = HttpClient::create();

        // Get estimated price
        $pricePrompt = "What is the estimated average price in Tunisian dinars (TND) for this car-related product: $itemName? Only give the number.";
        $priceResponse = $client->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=AIzaSyA1ZjFW8PkXB6HqhXO9WsWA1_3_GzXO1ks', [
            'json' => ['contents' => [['parts' => [['text' => $pricePrompt]]]]]
        ]);
        $priceData = $priceResponse->toArray(false);
        preg_match('/[\d.]+/', $priceData['candidates'][0]['content']['parts'][0]['text'] ?? '', $matches);
        $suggestedPrice = $matches[0] ?? null;

        // Get category
        $categoryPrompt = "For this car item: $itemName, select the most appropriate category. Options: Mechanics, Electronics, Electricity, Interior, Exterior, Cooling & Heating, Lubricants & Fluids, Accessories, Body Parts, Performance Parts.";
        $categoryResponse = $client->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=AIzaSyA1ZjFW8PkXB6HqhXO9WsWA1_3_GzXO1ks', [
            'json' => ['contents' => [['parts' => [['text' => $categoryPrompt]]]]]
        ]);
        $categoryData = $categoryResponse->toArray(false);
        $category = trim($categoryData['candidates'][0]['content']['parts'][0]['text'] ?? '');

        return $this->json([
            'suggestedPrice' => floatval($suggestedPrice),
            'suggestedCategory' => $category
        ]);
    }

    /**
     * Check if item name already exists.
     */
    #[Route('/items/check-name', name: 'check_item_name', methods: ['POST'])]
    public function checkItemName(Request $request, ItemRepository $itemRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $exists = $itemRepository->findOneBy(['nameItem' => $data['name']]) !== null;

        return $this->json(['exists' => $exists]);
    }

    /**
     * Check if a product is car-related using Gemini AI.
     */
    #[Route('/ai/is-car-related', name: 'ai_is_car_related', methods: ['POST'])]
    public function checkCarRelevance(Request $request, HttpClientInterface $client): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $itemName = $data['itemName'] ?? '';

        if (!$itemName) {
            return $this->json(['error' => 'No item name provided.'], 400);
        }

        $prompt = "Is the following product name related to cars, car maintenance, or car components? Only answer with 'yes' or 'no': \"$itemName\".";

        try {
            $response = $client->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=AIzaSyA1ZjFW8PkXB6HqhXO9WsWA1_3_GzXO1ks', [
                'json' => ['contents' => [['parts' => [['text' => $prompt]]]]]
            ]);
            $data = $response->toArray(false);
            $text = strtolower($data['candidates'][0]['content']['parts'][0]['text'] ?? '');
            $isCarRelated = str_contains($text, 'yes');

            return $this->json(['isCarRelated' => $isCarRelated]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Failed to contact Gemini API', 'details' => $e->getMessage()], 500);
        }
    }
}
