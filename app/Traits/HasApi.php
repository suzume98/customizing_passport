<?php

namespace App\Traits;

use Illuminate\Container\Container;
use  Laravel\Passport\Passport;
use App\Models\PersonalAccessTokenFactory;
use Laravel\Passport\{Token,ClientRepository};
use Carbon\Carbon;
trait HasApi
{
    /**
     * The current access token for the authentication user.
     *
     * @var \Laravel\Passport\Token
     */
    protected $accessToken;

    /**
     * Get all of the user's registered OAuth clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(Passport::clientModel(), 'user_id');
    }

    /**
     * Get all of the access tokens for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Passport::tokenModel(), 'user_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the current access token being used by the user.
     *
     * @return \Laravel\Passport\Token|null
     */
    public function token()
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $scope
     * @return bool
     */
    public function tokenCan($scope)
    {
        return $this->accessToken ? $this->accessToken->can($scope) : false;
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $scopes
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function createToken($name, array $scopes = [])
    {
        $personalAccessTokenFactory=Container::getInstance()->make(PersonalAccessTokenFactory::class);

        $personalAccessTokenFactory->setGuardProviderName($this->guardProviderName);
        
        return $personalAccessTokenFactory->make(
            $this->getKey(), $name, $scopes
        );
    }

    /**
     * Set the current access token for the user.
     *
     * @param  \Laravel\Passport\Token  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function passportToken(array $scopes = []){

        $tokenResult = $this->createToken( $this->tokenName ,  $scopes );
        $token = $tokenResult->token;
        $resultToken=$tokenResult->accessToken;
        
        $token->save();

        return response()->json([
            'code' => 200 , 
            'message' => 'success' ,
            'access_token' => $resultToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString() , 
        ]);
    }
}
