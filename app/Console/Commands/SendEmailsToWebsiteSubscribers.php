<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WebsiteSubscriber;

class SendEmailsToWebsiteSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send-emails-to-website-subscribers {website_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send the details of the specific website to the subscribers';

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
        $website_id = $this->argument('website_id');

        // Note: Get all the subscribers of the website
        $subscribers = WebsiteSubscriber::where('website_id', $website_id)->with(['user', 'website'])->get();
       
        foreach ($subscribers as $key => $subscriber) {
            $details = [
                'title'         => $subscriber->website->title,
                'description'   => $subscriber->website->description,
            ];

            \Mail::to($subscriber->user->email)->send(new \App\Mail\WebsitePostMail($details));

            echo "Email has been sent to: ".$subscriber->user->email."\n";
        }

        echo "Done sending emails!";

        return 0;
    }
}
