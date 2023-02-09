<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Products;
use App\Models\User;
use App\Notifications\EmailNotification;



class ProductsController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = new User();    
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Products::getallproducts();

        //check if we have any records
        if($result->isNotEmpty()){
            //return the records
            return $result;
        }else{
            //notify client we do not have any records
            return response()->json([
                   "message" => "No items listed",
                   "status" => "true"
                ],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          //lets first validate the $request
          $validator = $this->validator($request->all());

          //return validation response if error detected
           if ($validator -> fails()) {
             return response()->json($validator->errors(), 422);
           }
         
          //lets get the user id of the authenticated user
           $request->request->add(['created_by' => $this->user->id]); //add request created_by
   
   
           //lets add and get a result use create() and add all since we are not modifying the request 
           $result = Products::create($request->all());
   
           if($result){

                //lets send out notification email
                $project = [
                    'greeting' => 'Hi'.$this->user->name,
                    'body' => 'This product has been submitted by you.',
                    'thanks' => 'Thank you this is us',
                    'actionText' => 'View Product',
                    'actionURL' => url('/'),
                    'id' => 1
                ];
          
                Notification::send($this->user, new EmailNotification($project));

   
                return response()->json([
                      "message" => "Product ". $request->product_name." added successfully.",
                      "status" => "true"
                   ],200);
   
           }else{
   
                return response()->json([
                      "message" => "Error adding category ". $request->product_name.".",
                      "status" => "false"
                   ],200);
   
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Products::getselectedproduct($id);

        //check if we have any records
        if($result->isNotEmpty()){
            //return the records
            return $result;
        }else{
            //notify client we do not have any records
            return response()->json([
                   "message" => "No items listed matching search criteria",
                   "status" => "true"
                ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = Products::getselectedproduct($id);

        //check if we have any records
        if($result->isNotEmpty()){
            //return the records
            return $result;
        }else{
            //notify client we do not have any records
            return response()->json([
                   "message" => "No items listed matching search criteria",
                   "status" => "true"
                ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //lets first validate the $request
        $validator = $this->validator($request->all());

       //return validation response if error detected
        if ($validator -> fails()) {
          return response()->json($validator->errors(), 422);
        }
     

     //lets get the user id of the authenticated user
       $userid = 1;

       //check if selected $id exists
          if(Products::where('id',$id)->exists()){
            //the record exists proceed
             $products = Products::find($id);

                $products->product_name = is_null($request->product_name)? $products->product_name : $request->product_name;
                $products->category_id = is_null($request->category_id)? $products->category_id : $request->category_id;
                $products->created_by = $userid;

                //update
                $products->update();

                //return successfull response
                return response()->json([
                   "message" => "Product ". $request->product_name ." successfully updated",
                   "status" => "true"
                ],200);
             

          }else{
            //the record does not exist return response
            return response()->json([
                   "message" => "Selected product does not exist",
                   "status" => "false"
                ],200);
          }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //get the product corresponding to $id
        $products = Products::find($id);
        
            //check if product exists
            if($products){
               //proceed to delete 
                $products->delete();
               
               //return successful response
                return response()->json([
                       "message" => "Successfully deleted product ". $products->product_name ."",
                       "status" => "true"
                    ],200);

            }else{
              //no category with id specified exists
                return response()->json([
                       "message" => "No product to delete",
                       "status" => "false"
                    ],200);
             

            }
    }


      /**
     * Get a validator for an incoming products request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'product_name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'integer'],
        ]);
    }
}
