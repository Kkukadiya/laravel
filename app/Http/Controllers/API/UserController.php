<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

            $user = Auth::user();

            $success['message'] = "Logged in successfully.";
            $success['name'] = $user->first_name . " " . $user->last_name;
            $success['email'] = $user->email;
            $success['token'] = $user->createToken('MyApp')->accessToken;

            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['message'] = "Registered successfully.";
        $success['name'] = $user->first_name . " " . $user->last_name;
        $success['email'] = $user->email;
        $success['token'] = $user->createToken('MyApp')->accessToken;

        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList()
    {
        $users = new User;
        $users = $users->orderBy('id', 'DESC')->paginate(10);

        if ($users) {
            return response()->json(['success' => $users], $this->successStatus);
        }
    }

    /**
     * Add User api
     *
     * @return \Illuminate\Http\Response
     */
    public function addUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['message'] = "Record saved successfully.";

        return response()->json(['success' => $success], $this->successStatus);
    }

    /**
     * Edit api
     *
     * @return \Illuminate\Http\Response
     */
    public function editUser(Request $request)
    {
        if (!$request->id) {
            return response()->json(401, "Please provide id");
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,'. $request->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $userData = User::find($request->id);
        if ($userData) {
            $userData->first_name = $request->input('first_name');
            $userData->last_name = $request->input('last_name');
            $userData->email = $request->input('email');
            if ($userData->save()) {
                $success['message'] = "Record updated Successfully.";
                return response()->json(['success' => $success], $this->successStatus);
            } else {
                $success['message'] = "User details not updated.";
                return response()->json(['success' => $success], 401);
            }
        } else {
            $success['message'] = "User details not found.";
            return response()->json(['success' => $success], 404);
        }
    }
    
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
        if (!$request->id) {
            return response()->json(401, "Please provide id");
        }
        
        $userData = User::find($request->id);
        
        if ($userData) {
            return response()->json(['success' => $userData], $this->successStatus);
        } else {
            $success['message'] = "User details not found.";
            return response()->json(['success' => $success], 404);
        }
    }

    /**
     * Logut api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        $success['message'] = "Logged out successfully.";

        return response()->json(['success' => $success], $this->successStatus);
    }

}
