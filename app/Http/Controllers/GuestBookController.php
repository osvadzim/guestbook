<?php


namespace App\Http\Controllers;


use App\Http\Requests\GuestBookAnswerRequest;
use App\Http\Requests\GuestBookItemRequest;
use App\Http\Resources\GuestBookAnswerResource;
use App\Http\Resources\GuestBookItemResource;
use App\Models\GuestBookItem;
use App\Models\User;
use Illuminate\Http\Request;


class GuestBookController extends Controller
{
    private const DENIED_ERROR = 'You have no necessary permissions!';
    private const NOT_ADMIN_ERROR = 'Admins only can use this method';

    public function __construct()
    {
        // In my opinion, it will be better to set middlewares in route-file without using Route::apiResource()
        $this->middleware('auth:sanctum', ['except' => ['index', 'show']]);
        $this->middleware('limit:50', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $guestBookItemsCollection = GuestBookItem::query()
            ->with('answers')
            ->latest('id')
            ->limit($request->get('limit'))
            ->offset($request->get('offset', 0))
            ->get()
        ;

        return GuestBookItemResource::collection($guestBookItemsCollection);
    }

    public function show(Request $request, GuestBookItem $guestBookItem): GuestBookItemResource
    {
        return new GuestBookItemResource($guestBookItem);
    }

    public function store(GuestBookItemRequest $request): GuestBookItemResource
    {
        /** @var User $user */
        $user = auth()->user();

        $guestBookItem = $user->guestBookItems()->create([
            'content' => $request->get('content')
        ]);

        // TODO send notification to public channel

        return new GuestBookItemResource($guestBookItem);
    }

    public function update(Request $request)
    {
        // TODO
    }

    public function destroy(Request $request, GuestBookItem $guestBookItem)
    {
        abort_unless(
            auth()->user()->is_admin || $guestBookItem->user_id === auth()->id(),
            403,
            self::DENIED_ERROR
        );

        $guestBookItem->delete();
    }

    public function storeAnswer(GuestBookAnswerRequest $request, GuestBookItem $guestBookItem): GuestBookAnswerResource
    {
        abort_unless(auth()->user()->is_admin, 403, self::NOT_ADMIN_ERROR);

        $guestBookItem = $guestBookItem->answers()->create([
            'content' => $request->get('content'),
            'user_id' => auth()->id(),
        ]);

        // TODO send notification to private channel

        return new GuestBookAnswerResource($guestBookItem);
    }
}
