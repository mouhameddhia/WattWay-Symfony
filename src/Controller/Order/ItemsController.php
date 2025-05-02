<?php
namespace App\Controller\Order;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use App\Repository\ItemRepository;
use App\Entity\Item;
use App\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Make sure to import this
use App\Form\CreateItemType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\OrderRepository;
class ItemsController extends AbstractController
{
private $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;
}

#[Route('/items/create', name: 'create_item')]
public function create(Request $request): Response
{
$item = new Item();
$form = $this->createForm(CreateItemType::class, $item);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$imageFile = $form->get('image')->getData();

if ($imageFile) {
// Generate a file name using the item name
$fileName = $item->getNameItem() . '.' . $imageFile->guessExtension();

// Move the image to the public/images directory
$imageFile->move(
$this->getParameter('images_directory'), // This will refer to the 'public/images' directory
$fileName
);
}
$item->setQuantityItem(0);

// Persist the item entity (without the image)
$this->entityManager->persist($item);
$this->entityManager->flush();

// Redirect to the items list page
return $this->redirectToRoute('items');
}

return $this->render('backend/order/createItem.html.twig', [
'form' => $form->createView(),
]);
}



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

// Debugging: log image handling
if ($uploadedImage) {
$logger->info('New image uploaded: ', ['image' => $uploadedImage->getClientOriginalName()]);
dump('Uploaded image:', $uploadedImage);
} else {
$logger->info('No new image uploaded');
}

// Save image if a new one was uploaded
if ($uploadedImage) {
$imageName = $item->getNameItem() . '.png';
$uploadDir = $this->getParameter('kernel.project_dir') . '/public/images';

// Remove old image if it exists
$oldImagePath = $uploadDir . '/' . $oldName . '.png';
if ($filesystem->exists($oldImagePath)) {
$filesystem->remove($oldImagePath);
$logger->info('Old image removed: ', ['path' => $oldImagePath]);
dump('Old image removed:', $oldImagePath);
}

// Save new image as {itemName}.png
$uploadedImage->move($uploadDir, $imageName);
$logger->info('New image saved: ', ['path' => $uploadDir . '/' . $imageName]);
dump('New image saved at:', $uploadDir . '/' . $imageName);
} elseif ($oldName !== $item->getNameItem()) {
// Rename image file if item name changed and no new image was uploaded
$oldImagePath = $this->getParameter('kernel.project_dir') . '/public/images/' . $oldName . '.png';
$newImagePath = $this->getParameter('kernel.project_dir') . '/public/images/' . $item->getNameItem() . '.png';

if ($filesystem->exists($oldImagePath)) {
$filesystem->rename($oldImagePath, $newImagePath, true);
$logger->info('Image renamed from: ', ['oldPath' => $oldImagePath, 'newPath' => $newImagePath]);
dump('Image renamed:', ['old' => $oldImagePath, 'new' => $newImagePath]);
}
}

$em->flush();
$logger->info('Item updated and image processed.', ['itemId' => $item->getIdItem()]);
dump('Item updated:', $item);

return $this->redirectToRoute('items');
}

return $this->render('backend/order/editItem.html.twig', [
'form' => $form->createView(),
'originalNameItem' => $oldName,
]);
}


// Delete An Order
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
* @IsGranted("IS_AUTHENTICATED_FULLY")
*/

#[Route('/ai/suggest-price', name: 'ai_suggest_price', methods: ['POST'])]
public function suggestPrice(Request $request): JsonResponse
{
$data = json_decode($request->getContent(), true);
$itemName = $data['itemName'] ?? '';

if (!$itemName) {
return $this->json(['error' => 'Missing item name'], 400);
}

$client = HttpClient::create();

// Prompt 1: Get the price
$pricePrompt = "What is the estimated average price in Tunisian dinars (TND) for this car-related product: $itemName? Only give the number.";
$priceResponse = $client->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=AIzaSyAjXOcC_j_o-XtpjSp8dzMUZpmJxy3usRU', [
'json' => [
'contents' => [
['parts' => [['text' => $pricePrompt]]]
]
]
]);
$priceData = $priceResponse->toArray(false);
$priceText = $priceData['candidates'][0]['content']['parts'][0]['text'] ?? '';
preg_match('/[\d.]+/', $priceText, $matches);
$suggestedPrice = isset($matches[0]) ? floatval($matches[0]) : null;

// Prompt 2: Get the category
$categoryPrompt = "for this car item : $itemName, select the most appropriate category and return it. possible categories : Mechanics, Electronics, Electricity, Interior, Exterior, Cooling & Heating, Lubricants & Fluids, Accessories, Body Parts, Performance Parts. return one category";
$categoryResponse = $client->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=AIzaSyA1ZjFW8PkXB6HqhXO9WsWA1_3_GzXO1ks', [
'json' => [
'contents' => [
['parts' => [['text' => $categoryPrompt]]]
]
]
]);
$categoryData = $categoryResponse->toArray(false);
$categoryText = trim($categoryData['candidates'][0]['content']['parts'][0]['text'] ?? '');

return $this->json([
'suggestedPrice' => $suggestedPrice,
'suggestedCategory' => $categoryText
]);
}

#[Route('/items/check-name', name: 'check_item_name', methods: ['POST'])]
public function checkItemName(Request $request, ItemRepository $itemRepository): JsonResponse
{
$data = json_decode($request->getContent(), true);
$exists = $itemRepository->findOneBy(['nameItem' => $data['name']]) !== null;

return $this->json(['exists' => $exists]);
}

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
'json' => [
'contents' => [
['parts' => [['text' => $prompt]]]
]
]
]);

$data = $response->toArray(false);
$text = strtolower($data['candidates'][0]['content']['parts'][0]['text'] ?? '');

$isCarRelated = str_contains($text, 'yes');

return $this->json(['isCarRelated' => $isCarRelated]);

} catch (\Exception $e) {
return $this->json(['error' => 'Failed to contact Gemini API', 'details' => $e->getMessage()], 500);
}
}



#[Route('/api/validate-image', name: 'api_validate_image', methods: ['POST'])]
public function validateImage(Request $request, HttpClientInterface $http): JsonResponse
{
/** @var UploadedFile $image */
$image = $request->files->get('image');
$itemName = $request->request->get('itemName');

if (!$image || !$itemName) {
return new JsonResponse(['valid' => false, 'error' => 'Missing data'], 400);
}

try {
// Read and encode the image efficiently
$imageContent = file_get_contents($image->getPathname());
$imageBase64 = base64_encode($imageContent);
$mimeType = $image->getMimeType();

$apiKey = 'AIzaSyA1ZjFW8PkXB6HqhXO9WsWA1_3_GzXO1ks';

$response = $http->request('POST', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=' . $apiKey, [
'json' => [
'contents' => [
[
'parts' => [
[
'text' => "Please confirm if the object shown in this image is a \"$itemName\". If it clearly resembles a $itemName, answer 'yes'. If not, answer 'no'."
],
[
'inline_data' => [
'mime_type' => $mimeType,
'data' => $imageBase64
]
]
]
]
]
]
]);

$result = $response->toArray(false);
$replyText = strtolower(trim($result['candidates'][0]['content']['parts'][0]['text'] ?? ''));

$isValid = str_contains($replyText, 'yes');

return new JsonResponse([
'valid' => $isValid,
'ai_response' => $replyText // optional for debugging/insight
]);

} catch (\Throwable $e) {
return new JsonResponse([
'valid' => false,
'error' => 'AI validation failed',
'exception' => $e->getMessage()
], 500);
}
}

#[Route('/api/item/{id}/quantity', name: 'api_item_quantity', methods: ['GET'])]
public function getQuantity(int $id,ItemRepository $itemRepository,OrderRepository $orderRepository): JsonResponse {
$item = $itemRepository->find($id);
if (!$item) {
return new JsonResponse(['error' => 'Item not found'], 404);
}

// Find all items with the same name (stock tracked by name)
$allItemsWithSameName = $itemRepository->findBy(['nameItem' => $item->getNameItem()]);

// Classify them
$classified = $itemRepository->divideItemsByAdmin($allItemsWithSameName, $orderRepository);

// Calculate quantities
$quantities = $itemRepository->calculateItemQuantities($classified['clientItems'], $classified['adminItems']);

// Calculate available stock = adminQuantity - clientQuantity
$name = $item->getNameItem();
$adminQty = $quantities[$name]['adminQuantity'] ?? 0;
$clientQty = $quantities[$name]['clientQuantity'] ?? 0;
$availableQuantity = $adminQty - $clientQty;

return new JsonResponse(['availableQuantity' => $availableQuantity]);
}



}
?>