<?php

namespace App\Repository;

use App\Entity\Submission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Submission>
 */
class SubmissionRepository extends ServiceEntityRepository
{
    public function findByFilters(?string $status, ?string $urgency, ?string $keyword): array
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.idSubmission', 'DESC');

        if ($status) {
            $qb->andWhere('s.status = :status')
               ->setParameter('status', $status);
        }

        if ($urgency) {
            $qb->andWhere('s.urgencyLevel = :urgency')
               ->setParameter('urgency', $urgency);
        }

        if ($keyword) {
            $qb->andWhere('s.description LIKE :keyword')
               ->setParameter('keyword', '%'.$keyword.'%');
        }

        return $qb->getQuery()->getResult();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['idSubmission' => 'DESC']);
    }

    public function searchByKeywords(string $keywords): array
    {
        $qb = $this->createQueryBuilder('s');

        $terms = explode(' ', $keywords);
        foreach ($terms as $key => $term) {
            $qb->orWhere("s.description LIKE :term$key")
                ->setParameter("term$key", '%' . $term . '%');
        }

        return $qb
            ->orderBy('s.dateSubmission', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Submission::class);
    }

    //    /**
    //     * @return Submission[] Returns an array of Submission objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    public function findSubmissionsWithResponses()
    {
        return $this->createQueryBuilder('s')
            ->innerJoin('s.responses', 'r') // Changed to inner join
            ->addSelect('r')
            ->getQuery()
            ->getResult();
    }
    //    public function findOneBySomeField($value): ?Submission
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByDateRange(\DateTime $startDate, \DateTime $endDate): array
    {
        // Debug log
        error_log("Finding submissions between " . $startDate->format('Y-m-d H:i:s') . " and " . $endDate->format('Y-m-d H:i:s'));

        $qb = $this->createQueryBuilder('s')
            ->where('s.last_modified BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('s.last_modified', 'ASC');

        $query = $qb->getQuery();
        $sql = $query->getSQL();
        $params = $query->getParameters();

        // Debug log
        error_log("SQL Query: " . $sql);
        error_log("Parameters: " . json_encode($params->toArray()));

        $result = $query->getResult();

        // Debug log
        error_log("Found " . count($result) . " submissions");

        return $result;
    }

    public function getSubmissionStatistics(): array
    {
        // Get daily statistics
        $dailyStats = $this->createQueryBuilder('s')
            ->select('s.status', 'COUNT(s.idSubmission) as count', 'SUBSTRING(s.last_modified, 1, 10) as date')
            ->groupBy('s.status', 'date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();

        // Get urgency level statistics
        $urgencyStats = $this->createQueryBuilder('s')
            ->select('s.urgencyLevel', 'COUNT(s.idSubmission) as count')
            ->groupBy('s.urgencyLevel')
            ->getQuery()
            ->getResult();

        // Get status distribution
        $statusStats = $this->createQueryBuilder('s')
            ->select('s.status', 'COUNT(s.idSubmission) as count')
            ->groupBy('s.status')
            ->getQuery()
            ->getResult();

        // Get all responded submissions for response time calculation
        $respondedSubmissions = $this->createQueryBuilder('s')
            ->where('s.status = :status')
            ->setParameter('status', 'RESPONDED')
            ->getQuery()
            ->getResult();

        // Calculate average response time in PHP
        $totalResponseTime = 0;
        $responseCount = 0;
        foreach ($respondedSubmissions as $submission) {
            $responseTime = $submission->getLast_modified()->diff($submission->getDateSubmission())->h;
            if ($responseTime > 0) {
                $totalResponseTime += $responseTime;
                $responseCount++;
            }
        }
        $avgResponseTime = $responseCount > 0 ? $totalResponseTime / $responseCount : 0;

        // Format the results for the charts
        $chartData = [
            'timeline' => [
                'labels' => [],
                'datasets' => [
                    'PENDING' => [],
                    'APPROVED' => [],
                    'RESPONDED' => []
                ]
            ],
            'urgency' => [
                'labels' => [],
                'data' => []
            ],
            'status' => [
                'labels' => [],
                'data' => []
            ],
            'metrics' => [
                'avgResponseTime' => round($avgResponseTime, 2),
                'totalSubmissions' => array_sum(array_column($statusStats, 'count')),
                'completedSubmissions' => array_sum(array_filter(array_column($statusStats, 'count'), function($key) use ($statusStats) {
                    return $statusStats[$key]['status'] === 'RESPONDED';
                }, ARRAY_FILTER_USE_KEY))
            ]
        ];

        // Process daily statistics
        foreach ($dailyStats as $stat) {
            $date = $stat['date'];
            if (!in_array($date, $chartData['timeline']['labels'])) {
                $chartData['timeline']['labels'][] = $date;
            }
            $chartData['timeline']['datasets'][$stat['status']][] = (int)$stat['count'];
        }

        // Process urgency statistics
        foreach ($urgencyStats as $stat) {
            $chartData['urgency']['labels'][] = $stat['urgencyLevel'];
            $chartData['urgency']['data'][] = (int)$stat['count'];
        }

        // Process status statistics
        foreach ($statusStats as $stat) {
            $chartData['status']['labels'][] = $stat['status'];
            $chartData['status']['data'][] = (int)$stat['count'];
        }

        // Calculate estimated completion data
        $estimatedData = $this->calculateEstimatedCompletion();
        
        // Add estimated data to chartData
        $chartData['estimated'] = $estimatedData;

        return $chartData;
    }

    private function calculateEstimatedCompletion(): array
    {
        // Get all submissions
        $allSubmissions = $this->findAll();
        
        // Calculate average completion time for responded submissions
        $respondedSubmissions = array_filter($allSubmissions, function($submission) {
            return $submission->getStatus() === 'RESPONDED';
        });

        $totalCompletionTime = 0;
        $completionCount = 0;
        foreach ($respondedSubmissions as $submission) {
            $completionTime = $submission->getLast_modified()->diff($submission->getDateSubmission())->h;
            if ($completionTime > 0) {
                $totalCompletionTime += $completionTime;
                $completionCount++;
            }
        }

        $averageCompletionTime = $completionCount > 0 ? $totalCompletionTime / $completionCount : 24; // Default to 24 hours if no data

        // Calculate estimated completion for pending submissions
        $pendingSubmissions = array_filter($allSubmissions, function($submission) {
            return $submission->getStatus() === 'PENDING';
        });

        $estimatedCompletion = [];
        $currentDate = new \DateTime();
        $remainingSubmissions = count($pendingSubmissions);
        $daysToComplete = ceil($remainingSubmissions * ($averageCompletionTime / 24)); // Convert hours to days

        // Generate estimated completion data
        for ($i = 0; $i <= $daysToComplete; $i++) {
            $date = (clone $currentDate)->modify("+$i days")->format('Y-m-d');
            $estimatedCompletion[$date] = max(0, $remainingSubmissions - ceil(($i * $remainingSubmissions) / $daysToComplete));
        }

        return [
            'averageCompletionTime' => round($averageCompletionTime, 2),
            'remainingSubmissions' => $remainingSubmissions,
            'estimatedDaysToComplete' => $daysToComplete,
            'completionData' => $estimatedCompletion
        ];
    }

    public function getChartData(): array
    {
        // Get daily statistics
        $dailyStats = $this->createQueryBuilder('s')
            ->select('s.status', 'COUNT(s.idSubmission) as count', 'SUBSTRING(s.last_modified, 1, 10) as date')
            ->groupBy('s.status', 'date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();

        // Get urgency level statistics
        $urgencyStats = $this->createQueryBuilder('s')
            ->select('s.urgencyLevel', 'COUNT(s.idSubmission) as count')
            ->groupBy('s.urgencyLevel')
            ->getQuery()
            ->getResult();

        // Get status distribution
        $statusStats = $this->createQueryBuilder('s')
            ->select('s.status', 'COUNT(s.idSubmission) as count')
            ->groupBy('s.status')
            ->getQuery()
            ->getResult();

        // Get all responded submissions for response time calculation
        $respondedSubmissions = $this->createQueryBuilder('s')
            ->where('s.status = :status')
            ->setParameter('status', 'RESPONDED')
            ->getQuery()
            ->getResult();

        // Calculate average response time in PHP
        $totalResponseTime = 0;
        $responseCount = 0;
        foreach ($respondedSubmissions as $submission) {
            $responseTime = $submission->getLast_modified()->diff($submission->getDateSubmission())->h;
            if ($responseTime > 0) {
                $totalResponseTime += $responseTime;
                $responseCount++;
            }
        }
        $avgResponseTime = $responseCount > 0 ? $totalResponseTime / $responseCount : 0;

        // Format the results for the charts
        $chartData = [
            'timeline' => [
                'labels' => [],
                'datasets' => [
                    'PENDING' => [],
                    'APPROVED' => [],
                    'RESPONDED' => []
                ]
            ],
            'urgency' => [
                'labels' => [],
                'data' => []
            ],
            'status' => [
                'labels' => [],
                'data' => []
            ],
            'metrics' => [
                'avgResponseTime' => round($avgResponseTime, 2),
                'totalSubmissions' => array_sum(array_column($statusStats, 'count')),
                'completedSubmissions' => array_sum(array_filter(array_column($statusStats, 'count'), function($key) use ($statusStats) {
                    return $statusStats[$key]['status'] === 'RESPONDED';
                }, ARRAY_FILTER_USE_KEY))
            ]
        ];

        // Process daily statistics
        foreach ($dailyStats as $stat) {
            $date = $stat['date'];
            if (!in_array($date, $chartData['timeline']['labels'])) {
                $chartData['timeline']['labels'][] = $date;
            }
            $chartData['timeline']['datasets'][$stat['status']][] = (int)$stat['count'];
        }

        // Process urgency statistics
        foreach ($urgencyStats as $stat) {
            $chartData['urgency']['labels'][] = $stat['urgencyLevel'];
            $chartData['urgency']['data'][] = (int)$stat['count'];
        }

        // Process status statistics
        foreach ($statusStats as $stat) {
            $chartData['status']['labels'][] = $stat['status'];
            $chartData['status']['data'][] = (int)$stat['count'];
        }

        // Calculate estimated completion data
        $estimatedData = $this->calculateEstimatedCompletion();
        
        // Add estimated data to chartData
        $chartData['estimated'] = $estimatedData;

        return $chartData;
    }

    public function getDetailedStatistics(): array
    {
        // Get submissions by status and urgency
        $statusUrgencyStats = $this->createQueryBuilder('s')
            ->select('s.status', 's.urgencyLevel', 'COUNT(s.idSubmission) as count')
            ->groupBy('s.status', 's.urgencyLevel')
            ->getQuery()
            ->getResult();

        // Get submissions by status
        $statusStats = $this->createQueryBuilder('s')
            ->select('s.status', 'COUNT(s.idSubmission) as count')
            ->groupBy('s.status')
            ->getQuery()
            ->getResult();

        // Get responded submissions for response time calculation
        $respondedSubmissions = $this->createQueryBuilder('s')
            ->where('s.status = :status')
            ->setParameter('status', 'RESPONDED')
            ->getQuery()
            ->getResult();

        // Calculate response time by urgency in PHP
        $responseTimeByUrgency = [];
        foreach ($respondedSubmissions as $submission) {
            $urgency = $submission->getUrgencyLevel();
            $responseTime = $submission->getLastModified()->diff($submission->getDateSubmission())->h;
            
            if (!isset($responseTimeByUrgency[$urgency])) {
                $responseTimeByUrgency[$urgency] = [
                    'total' => 0,
                    'count' => 0
                ];
            }
            
            $responseTimeByUrgency[$urgency]['total'] += $responseTime;
            $responseTimeByUrgency[$urgency]['count']++;
        }

        // Format the results
        $stats = [
            'statusUrgency' => [],
            'status' => [
                'labels' => [],
                'data' => []
            ],
            'responseTime' => [
                'labels' => [],
                'data' => []
            ]
        ];

        // Process status and urgency statistics
        foreach ($statusUrgencyStats as $stat) {
            if (!isset($stats['statusUrgency'][$stat['status']])) {
                $stats['statusUrgency'][$stat['status']] = [];
            }
            $stats['statusUrgency'][$stat['status']][$stat['urgencyLevel']] = (int)$stat['count'];
        }

        // Process status statistics
        foreach ($statusStats as $stat) {
            $stats['status']['labels'][] = $stat['status'];
            $stats['status']['data'][] = (int)$stat['count'];
        }

        // Process response time statistics
        foreach ($responseTimeByUrgency as $urgency => $data) {
            $stats['responseTime']['labels'][] = $urgency;
            $stats['responseTime']['data'][] = $data['count'] > 0 ? round($data['total'] / $data['count'], 2) : 0;
        }

        return $stats;
    }

    public function getBurnDownData(): array
    {
        // Get all submissions ordered by last_modified date
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.last_modified', 'ASC');

        $submissions = $qb->getQuery()->getResult();

        // Initialize data structure with column headers
        $data = [
            ['Date', 'To Do', 'Doing', 'Done', 'Total']
        ];

        // Track counts over time
        $counts = [
            'PENDING' => 0,
            'APPROVED' => 0,
            'RESPONDED' => 0
        ];

        // Process each submission
        foreach ($submissions as $submission) {
            $date = $submission->getLastModified()->format('Y-m-d');
            
            // Add date if not already present
            if (!in_array($date, array_column($data, 0))) {
                $data[] = [
                    $date,
                    $counts['PENDING'],
                    $counts['APPROVED'],
                    $counts['RESPONDED'],
                    array_sum($counts)
                ];
            }

            // Update counts based on current status
            $counts[$submission->getStatus()]++;
        }

        // Add final counts
        $data[] = [
            'Current',
            $counts['PENDING'],
            $counts['APPROVED'],
            $counts['RESPONDED'],
            array_sum($counts)
        ];

        return $data;
    }

    public function searchAndSort(string $searchTerm = '', string $sortBy = 'dateSubmission', string $sortOrder = 'DESC', string $status = 'ALL'): array
    {
        $qb = $this->createQueryBuilder('s');

        if ($searchTerm) {
            $qb->andWhere('s.description LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($status !== 'ALL') {
            $qb->andWhere('s.status = :status')
               ->setParameter('status', $status);
        }

        // Validate and set sort field
        $validSortFields = ['dateSubmission', 'preferredAppointmentDate', 'status', 'urgencyLevel'];
        $sortField = in_array($sortBy, $validSortFields) ? $sortBy : 'dateSubmission';
        
        // Validate sort order
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $qb->orderBy('s.' . $sortField, $sortOrder);

        return $qb->getQuery()->getResult();
    }

    public function createSearchQuery(string $searchTerm = '', string $sortBy = 'dateSubmission', string $sortOrder = 'DESC', string $status = 'ALL')
    {
        $qb = $this->createQueryBuilder('s');

        if ($searchTerm) {
            $qb->andWhere('s.description LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($status !== 'ALL') {
            $qb->andWhere('s.status = :status')
               ->setParameter('status', $status);
        }

        // Validate and set sort field
        $validSortFields = ['dateSubmission', 'preferredAppointmentDate', 'status', 'urgencyLevel'];
        $sortField = in_array($sortBy, $validSortFields) ? $sortBy : 'dateSubmission';
        
        // Validate sort order
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        $qb->orderBy('s.' . $sortField, $sortOrder);

        return $qb->getQuery();
    }
}
