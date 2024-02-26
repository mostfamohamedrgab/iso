<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectSelectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $projectUrl;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project, $projectUrl,$user)
    {
        $this->project = $project;
        $this->projectUrl = $projectUrl;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.project_selected_notification')
                    ->subject('you have been selected to participate in [ '. $this->project->title.' ] assessment');
    }
}
