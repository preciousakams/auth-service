<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateExternalRequestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external-request:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate external request data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $externalRequests = \DB::connection('wallet_service')->table('external_requests')->get();

        foreach ($externalRequests as $request){
            \DB::table('external_requests')->insert([
                'id' => $request->id,
                'service' => $request->service,
                'endpoint' => $request->endpoint,
                'url' => $request->url,
                'reference' => $request->reference,
                'request' => $request->request,
                'response' => $request->response,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
            ]);
        }

        return 0;
    }
}
