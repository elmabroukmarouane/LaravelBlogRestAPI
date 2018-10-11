<?php

    namespace App\Http\Controllers\Auth;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Faker\Factory as Faker;
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
            $user = User::where('email', '=', $request->only('email'))->first();
            $user_array = array(
                'id' => $user->id,
                'name' => $user->name,
                'birthdate' => $user->birthdate,
                'email' => $user->email,
                'user_role' => $user->user_role,
                'image' => $user->image,
                'token' => $token
            );
            return response()->json([
                'user' => $user_array
            ], 200);
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
                'birthdate' => 'required|date',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $datetime = new \Datetime($request->get('birthdate'));
            $faker = Faker::create();
            $user = User::create([
                'name' => $request->get('name'),
                'birthdate' => $datetime->format('Y-m-d'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'user_role' => 'super_admin',
                'image' => $faker->imageUrl($width = 640, $height = 480)
            ]);
            $token = JWTAuth::fromUser($user);
            $user_array = array(
                'id' => $user->id,
                'name' => $user->name,
                'birthdate' => $user->birthdate,
                'email' => $user->email,
                'user_role' => $user->user_role,
                'image' => $user->image,
                'token' => $token
            );
            return response()->json([
                'user' => $user_array
            ], 201);
        }

        /* public function getAuthenticatedUser()
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
        } */
    }