<?php

namespace App\Console\Commands\Mail;

use Illuminate\Console\Command;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Sichikawa\LaravelSendgridDriver\SendGrid;
use Sichikawa\LaravelSendgridDriver\Transport\SendgridTransport;

class ApiSimpleMultiTo extends Command
{
    use SendGrid;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendgrid:mail-api-simple-multi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        /** @var Mailer $mailer */
        $mailer = app('mailer');
        $mailer->getSwiftMailer()->registerPlugin(new MailTracker());
        $mailer->send([], [], function (Message $message) {
            $message
                ->subject('[Sample] simple mail.')
                ->to('ichikawa.shingo.0829+reply@gmail.com')
                ->from("ichikawa.shingo.0829@gmail.com", "Shingo Ichikawa")
                ->replyTo('ichikawa.shingo.0829+reply@gmail.com', "s-ichikawa")
                ->embedData(self::sgEncode([
                    'personalizations' => [
                        [
                            'to' => [
                                [
                                    'email' => 'ichikawa.shingo.0829+to1@gmail.com',
                                    'name' => 's-ichikawa1',
                                ],
                                [
                                    'email' => 'ichikawa.shingo.0829+to2@gmail.com',
                                    'name' => 's-ichikawa2',
                                ],
                            ],
                            'dynamic_template_data' => [
                                'name' => 'Shingo Ichikawa san',
                            ],
                            'custom_args' => [
                                'personalized_args' => '🍣',
                            ]
                        ],
                    ],
                    'custom_args' => [
                        'global_arg' => '🍺'
                    ],
                    'template_id' => config('services.sendgrid.templates.dynamic_sample')
                ]), SendgridTransport::SMTP_API_NAME);
        });
    }
}
