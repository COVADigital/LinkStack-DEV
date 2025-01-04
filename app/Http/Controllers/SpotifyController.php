namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyController extends Controller
{
    private $clientId = '51c75178df68472680ffcd43e658f4bb';
    private $clientSecret = '56d45c2a532b413eab1f2179533ddb19';
    private $redirectUri = 'https://link.app.covadigital.io/dashboard';

    public function redirectToSpotify()
    {
        $session = new Session(
            $this->clientId,
            $this->clientSecret,
            $this->redirectUri
        );

        $options = [
            'scope' => [
                'user-read-private',
                'user-read-email',
                'playlist-read-private',
            ],
        ];

        return redirect($session->getAuthorizeUrl($options));
    }

    public function handleCallback(Request $request)
    {
        $session = new Session(
            $this->clientId,
            $this->clientSecret,
            $this->redirectUri
        );

        $session->requestAccessToken($request->query('code'));

        // Store tokens for the user
        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();

        auth()->user()->update([
            'spotify_access_token' => $accessToken,
            'spotify_refresh_token' => $refreshToken,
        ]);

        return redirect()->route('spotify.analytics');
    }

    public function fetchAnalytics()
    {
        $api = new SpotifyWebAPI();
        $api->setAccessToken(auth()->user()->spotify_access_token);

        // Fetch analytics (e.g., playlists)
        $playlists = $api->getUserPlaylists();

        return view('dashboard.spotify', compact('playlists'));
    }
}
