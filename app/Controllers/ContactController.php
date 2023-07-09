<?php

namespace App\Controllers;

use App\Exceptions\BadRequestException;
use App\Repositories\ContactRepository;

class ContactController extends Controller
{
    public function __construct(private readonly ContactRepository $repository)
    {
    }

    public function index()
    {
        return $this->response($this->repository->index());
    }

    public function get(int $id)
    {
        return $this->response($this->repository->get($id));
    }


    public function create()
    {
        $request = request();
        if (!$request->name || empty($request->name)) {
            throw new BadRequestException('Campo name é obrigatório');
        }

        return $this->response($this->repository->save($request->all()));
    }

    public function update(int $id)
    {
        $request = request();
        return $this->response($this->repository->update($id, $request->all()));
    }

    public function delete(int $id)
    {
        return $this->response($this->repository->delete($id));
    }
}
