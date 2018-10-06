<?php

    namespace App\Http\Controllers\Auth;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use JWTAuth;
    use App\User;
    use Tymon\JWTAuth\Exceptions\JWTException;

    class LoginController extends Controller
    {
        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            return response()->json(compact('token'));
        } 

        public function logout(Request $request)
        {
            $header = $request->header('Authorization');
            $header_array = explode(' ', $header);
            $token = $header_array[1];
            try {
                JWTAuth::invalidate($token);
                return response()->json([
                    'success' => true,
                    'message' => 'User logged out successfully'
                ]);
            } catch (JWTException $exception) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, the user cannot be logged out'
                ], 500);
            }
        }

        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $user = User::create([
                'name' => $request->get('name'),
                'birthdate' => $request->get('birthdate'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'user_role' => 'super_admin'
            ]);
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user','token'),201);
        }

        public function getAuthenticatedUser()
        {
            try {
                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
                }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
        }
    }