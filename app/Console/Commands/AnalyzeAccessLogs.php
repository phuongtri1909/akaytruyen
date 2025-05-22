<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AnalyzeAccessLogs extends Command
{
    protected $signature = 'logs:analyze-access {--limit=10 : Number of top results to show}';
    protected $description = 'Analyze access logs to find most frequently accessed routes and IPs';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Analyzing access logs...');
        
        $logPath = storage_path('logs/iplog.log');
        
        if (!File::exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return 1;
        }
        
        $logContent = File::get($logPath);
        $lines = explode("\n", $logContent);
        
        $ipCounts = [];
        $routeCounts = [];
        
        foreach ($lines as $line) {
            if (strpos($line, 'IP:') !== false && strpos($line, 'Route:') !== false) {
                // Extract IP address
                preg_match('/IP: ([\d\.]+)/', $line, $ipMatches);
                if (isset($ipMatches[1])) {
                    $ip = $ipMatches[1];
                    if (!isset($ipCounts[$ip])) {
                        $ipCounts[$ip] = 0;
                    }
                    $ipCounts[$ip]++;
                }
                
                // Extract route
                preg_match('/Route: ([^\s|]+)/', $line, $routeMatches);
                if (isset($routeMatches[1])) {
                    $route = $routeMatches[1];
                    if (!isset($routeCounts[$route])) {
                        $routeCounts[$route] = 0;
                    }
                    $routeCounts[$route]++;
                }
            }
        }
        
        // Sort by count (descending)
        arsort($ipCounts);
        arsort($routeCounts);
        
        $limit = (int) $this->option('limit');
        
        $this->info("\nTop {$limit} IP addresses:");
        $this->table(['IP Address', 'Count'], $this->formatForTable(array_slice($ipCounts, 0, $limit, true)));
        
        $this->info("\nTop {$limit} routes:");
        $this->table(['Route', 'Count'], $this->formatForTable(array_slice($routeCounts, 0, $limit, true)));
    }
    
    private function formatForTable(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [$key, $value];
        }
        return $result;
    }
} 