<?php

namespace App\Service\ML;

use App\Entity\Submission;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\CrossValidation\Metrics\Accuracy;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PriorityScoringService
{
    private string $modelPath;
    private ?PersistentModel $model = null;

    public function __construct(ParameterBagInterface $params)
    {
        $this->modelPath = $params->get('kernel.project_dir') . '/var/ml/priority_model.rbx';
    }

    public function predictPriority(Submission $submission): string
    {
        if (!$this->model) {
            $this->loadModel();
        }

        // Extract features from submission
        $features = $this->extractFeatures($submission);
        
        // Create an unlabeled dataset
        $dataset = new Unlabeled([$features]);
        
        // Make prediction
        $prediction = $this->model->predict($dataset);
        
        return $prediction[0];
    }

    public function trainModel(array $submissions): void
    {
        // Prepare training data
        $samples = [];
        $labels = [];
        
        foreach ($submissions as $submission) {
            $samples[] = $this->extractFeatures($submission);
            $labels[] = $submission->getUrgencyLevel();
        }
        
        // Create labeled dataset
        $dataset = new Labeled($samples, $labels);
        
        // Initialize the classifier
        $estimator = new KNearestNeighbors(3);
        
        // Train the model
        $estimator->train($dataset);
        
        // Create persistent model
        $this->model = new PersistentModel($estimator, new Filesystem($this->modelPath));
        
        // Save the model
        $this->model->save();
    }

    private function extractFeatures(Submission $submission): array
    {
        return [
            // Convert description to numerical features (length, word count)
            strlen($submission->getDescription()),
            str_word_count($submission->getDescription()),
            
            // Time-based features
            $submission->getDateSubmission()->getTimestamp(),
            $submission->getPreferredAppointmentDate()->getTimestamp(),
            
            // Convert status to numerical value
            $this->statusToNumber($submission->getStatus()),
            
            // Convert preferred contact method to numerical value
            $this->contactMethodToNumber($submission->getPreferredContactMethod()),
        ];
    }

    private function statusToNumber(string $status): int
    {
        return match ($status) {
            'pending' => 0,
            'in_progress' => 1,
            'completed' => 2,
            default => 0,
        };
    }

    private function contactMethodToNumber(?string $method): int
    {
        return match ($method) {
            'sms' => 0,
            'phone' => 1,
            'email' => 2,
            default => 0,
        };
    }

    private function loadModel(): void
    {
        if (file_exists($this->modelPath)) {
            $this->model = PersistentModel::load(new Filesystem($this->modelPath));
        } else {
            throw new \RuntimeException('Model file not found. Please train the model first.');
        }
    }
} 