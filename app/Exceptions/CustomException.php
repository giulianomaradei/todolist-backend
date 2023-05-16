<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;

class CustomException extends Exception
{
    use ApiResponser;

    public function report()
    {
       //
    }

    public function render($request)
    {
        $result = ! env('APP_DEBUG') ?
            $request->all() :
            [
                'file'    => $this->file,
                'line'    => $this->line,
                'request' => $request->all()
            ];

        return $this->error($this->message, $this->code, $result );
    }
}
