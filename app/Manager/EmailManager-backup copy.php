<?php

namespace App\Manager;

// use App\Email;
// use App\EmailTranslation;
use App\Notifications\EmailNotification;
// use App\Notifications\Invoice;
use App\Constants\Constant;


class EmailManager
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    // public function __construct(Email $email,EmailTranslation $emailTranslation)
    // {
    //     $this->email = $email;
    //     $this->emailTranslation = $emailTranslation;
    // }

    /**
     * @return Email
     */
    // public function email()
    // {
    //     return $this->email;
    // }

    /**
     * List of all email template
     * @param $request
     * @param $limit
     */
    // function list($request, int $limit) {
    //     $email = $this->email->with('detail:email_id,title,subject')->sortable()->orderBy('created_at', 'desc');
    //     if ($request->has('title')) {
    //         $name = $request->query('title');
    //         $email->whereHas('detail', function ($q) use ($name) {
    //             $q->where('title', 'ILIKE', '%' . $name . '%');
    //         });
    //     }
    //     $data = $email->paginate($limit);
    //     return $data;
    // }

    /**
     * Get first
     * @param $id
     */
    // public function get($id)
    // {
    //     return $this->email->findOrFail($id);
    // }

    /**
     * @param $request
     */
    // public function create($request)
    // {
    //     $data = $this->email;
    //     if (!empty($request->get('id'))) {
    //         $data = $this->email->findOrFail($request->get('id'));
    //     }
    //     $translation = [];
    //     if ($data->save()) {
    //         foreach ($request->translation as $value) {
    //             $item = new EmailTranslation();
    //             if (!empty($value['id'])) {
    //                 $item = $this->emailTranslation->findOrFail($value['id']);
    //             }else{
    //                 $item->keyword = $value['keyword'];
    //             }
    //             $item->locale = $value['locale'];
    //             $item->title = $value['title'];
    //             $item->description = $value['description'];
    //             $item->subject = $value['subject'];
    //             //$item->keyword = $value['description'];
    //             $translation[] = $item;
    //         }
    //     }
    //     $data->translation()->saveMany($translation);
    // }

    /**
     * @param $id
     * Hasman
     */
    // public function getbyId($id)
    // {
    //     $email = $this->email->with(['translation'])->findOrFail($id);
    //     $array = [];
    //     if (!empty($email)) {
    //         foreach ($email->translation as $key => $value) {
    //             $array[$value->locale]['id'] = $value->id;
    //             $array[$value->locale]['locale'] = $value->locale;
    //             $array[$value->locale]['title'] = $value->title;
    //             $array[$value->locale]['description'] = $value->description;
    //             $array[$value->locale]['keyword'] = $value->keyword;
    //             $array[$value->locale]['subject'] = $value->subject;
    //         }
    //         $email->translation = $array;
    //     }
    //     return $email;
    // }


    /**
     * Send Email
     *
     * @param $id
     * @param $user
     * @param $url
     */
    public function sendEmail($slug,$user,$url,$notification){
        $when = now()->addSeconds(Constant::SHOULD_QUEUE);
        $user->notify((new EmailNotification($slug,$user,$url,$notification))->delay($when));
        return true;
    }


    /**
     * Send Email for invoice
     *
     * @param $id
     * @param $user
     * @param $url
     */
    // public function sendInvoice($id,$user,$name,$url,$invoiceId){
    //     $when = now()->addSeconds(Constant::SHOULD_QUEUE);
    //     $user->notify((new Invoice($id,$user,$url,$invoiceId,$name))->delay($when));
    //     return true;
    // }






}
