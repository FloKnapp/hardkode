<?php


namespace Hardkode\Controller;


class UploadController extends AbstractController
{

    public function process()
    {
        $data = (string)$this->getRequest()->getBody()->getContents();

        var_dump($data);

    }

}