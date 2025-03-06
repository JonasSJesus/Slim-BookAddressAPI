<?php

namespace Agenda\Controllers;

interface ControllerInterface
{
    public function read(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
    public function readOne(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface;
}
