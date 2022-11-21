<?php

namespace App\Services;

use Google_Client;

class GoogleSheets
{
    private $service;
    private $sheetID;
    private $range;
    private $filter_value;
    private $lastEntry;

    /**
     * @param $sheetID
     * @param $range
     * @throws \Google\Exception
     */
    public function __construct($sheetID, $range)
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');
        $client->setAuthConfig(__DIR__ . '/credential.json');
        $this->service = new \Google_Service_Sheets($client);
        $this->sheetID = $sheetID;
        $this->range = $range;
    }

    public function getAllEntries()
    {
        $response = $this->service->spreadsheets_values->get($this->sheetID, $this->range);
        $values = $response->getValues();
        array_shift($values);

        return $values;
    }

    public function getLastEntry()
    {
        $data = $this->getAllEntries();

        if(empty($data)) {
            return false;
        }

        $this->lastEntry = end($data);

        return $this->lastEntry;
    }

    public function getByPhone($phone)
    {
        $this->filter_value = $phone;
        $entries = $this->getAllEntries();
        return array_filter(
           $entries,
           function($a){
               return $a[Tithing::PHONE] == $this->filter_value;
           });
    }

    public function addEntry($data)
    {
        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $this->service->spreadsheets_values->append($this->sheetID, $this->range, $body, $params);
    }
}
