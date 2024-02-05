<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\ICategoryRepository;
use App\Repositories\IOrderRepository;
use App\Repositories\IUserRepository;
use App\Requests\dashboard\CreateUpdateOrderRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(IOrderRepository $orderRepository ,
                                IUserRepository $Iuser ,
                                ICategoryRepository $Icat
                                ){

        $this->orderRepository = $orderRepository;
        $this->Iuser = $Iuser;
        $this->Icat = $Icat;
       
    }


    public function index()
    {
        $orders = $this->orderRepository->getAll();
        return view('dashboard.orders.index' , compact('orders'));
    }

    public function create()
    {
        return view('dashboard.orders.create');
    }

    public function store(CreateUpdateOrderRequest $request)
    {
        $this->orderRepository->create($request->all());
        return response()->json();
    }


    public function edit($id)
    {
        $order = $this->orderRepository->findOne($id);
        $users = $this->Iuser->AllUsers();
        $categories = $this->Icat->getAllActive();
        return view('dashboard.orders.edit' , compact('order' , 'users' , 'categories'));
    }

    public function update(CreateUpdateOrderRequest $request , $id)
    {
        $this->orderRepository->update($request->validated() , $id);
        return response()->json();
    }


    public function destroy($id)
    {
        $this->orderRepository->forceDelete($id);
        return response()->json();

    }


    
    public function show($id)
    {
        $order = $this->orderRepository->findOne($id);
        return view('dashboard.orders.show' , compact('order'));
    }

    public function ordersByStatus($status)
    {
        $statues = [];
        if($status == "pending")
        $statues = ['pending'];

        if($status == "preparing")
        $statues = ['preparing' , 'delegate_accept'];

        if($status == "done")
        $statues = ['done'];

        if($status == "cancelled")
        $statues = ['cancelled'];

        $orders = $this->orderRepository->getWhereIn('status' ,  $statues);
        return view('dashboard.orders.index' , compact('orders'));

    }


    public function userOrders($userId)
    {
        $orders = $this->orderRepository->getWhere(['user_id' =>  $userId]);
        return view('dashboard.users.orders' , compact('orders'));
    }

}