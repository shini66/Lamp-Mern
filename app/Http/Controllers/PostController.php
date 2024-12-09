<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        $posts = $this->postRepository->getAllPosts();
        return response()->json($posts);
    }

    public function show($id)
    {
        $post = $this->postRepository->getPostById($id);
        return response()->json($post);
    }
}
