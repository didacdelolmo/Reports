<?php

declare(strict_types=1);


namespace diduhless\reports\report;


class ReportFactory {

    /** @var Report[] */
    static private $reports = [];

    /**
     * @return Report[]
     */
    static public function getReports(): array {
        return self::$reports;
    }

    static public function hasReport(string $target_name): bool {
        return isset(self::$reports[$target_name]);
    }

    static public function registerReport(Report $report): void {
        $target_name = $report->getTarget()->getUsername();
        self::dismissReport($target_name);
        self::$reports[$target_name] = $report;
    }

    static public function dismissReport(string $target_name): void {
        if(self::hasReport($target_name)) {
            unset(self::$reports[$target_name]);
        }
    }

}