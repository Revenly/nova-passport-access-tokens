# Nova Passport Access Token Manager
Manage Laravel Passport personal access tokens.

## Requirements
- PHP >= 7.1.3
- Laravel 5.8.* (https://laravel.com)
- Laravel Nova 2.* (https://nova.laravel.com)


## Installation
1. ```sh
   composer require "64robots/nova-passport-access-tokens:*"
   ```
   
2. Add the tool to your `app\Providers\NovaServiceProvider.php`:
   ```php
    public function tools()
    {
        return [
            // ...
            new R64\NovaPassportAccessTokens\NovaPassportAccessToken\NovaPassportAccessToken,
        ];
    }
   ```

3. Implement `R64\NovaPassportAccessTokens\NovaIssuableToken` in your `App\Models\Users`

```php
namespace App\Models\User;
use Illuminate\Database\Eloquent\Collection;


class User extends Authenticatable implements NovaIssuableToken
{

    public static function getForNova(): Collection 
    {
        //
    }

}
```

4. Implement `R64\NovaPassportAccessTokens\ScopeForNova` in your `App\Models\Token`

```php
namespace App\Models\Token;
use R64\NovaPassportAccessTokens\ScopeForNova;

class Token extends \Laravel\Passport\Token implements ScopeForNova
{
    public function scopeForNova($query)
    {
        //
    }
}
```

5. Use `App\Models\Token` in `AppServiceProvider`

```php
namespace App\Providers;

use App\Models\Token;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Passport::useTokenModel(Token::class);
    }
}
```

## Usage
### Nova Tools
#### Passport Management
<img width="1633" alt="Screen Shot 2019-08-22 at 4 55 38 PM" src="./screenshots/usage.png">
