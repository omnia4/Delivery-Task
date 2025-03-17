<?php 

namespace App\Services;

class Response 
{
    public $code    = 200;
    public $status  = 'success';
    public $message = '';
    public $data    = null;

    public function code($code)
    {
        $this->code = $code;
        $this->status = $this->code >= 200 && $this->code < 300 ? 'success' : 'fail';
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function json()
    { 
        $response = [
            'status'    => $this->status,
            'message'   => $this->message
        ];
        
        if ($this->data !== null) {
            $response['data'] = $this->data;
        }

        return response()->json($response, $this->code);
    }
    
}