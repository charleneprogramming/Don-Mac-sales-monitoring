namespace App\Application\UseCases;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Eloquent\UserRepository;
use Illuminate\Support\Facades\Hash;

class LoginUseCase
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $email, string $password): bool
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return false; // User not found
        }

        return Hash::check($password, $user->getPassword());
    }
}
