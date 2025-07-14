{{-- resources/views/components/rating-modal.blade.php --}}
<div x-data="{ open:false }">
    {{-- ボタン --}}
    <button @click="open=true" class="bg-green-600 text-white px-4 py-1 rounded">
        取引を完了する
    </button>

    {{-- オーバーレイ --}}
    <div x-show="open"
         class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded w-80" @click.away="open=false">
            <h3 class="font-bold mb-3">評価を送信</h3>
            <form method="POST" action="{{ route('trades.complete', $trade) }}">
                @csrf
                <select name="score" class="border mb-3 w-full">
                    @for ($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }} ★</option>
                    @endfor
                </select>
                <textarea name="comment" rows="3" class="border w-full mb-3"
                          placeholder="コメント (任意)"></textarea>
                <div class="text-right space-x-2">
                    <button type="button" @click="open=false"
                            class="px-3 py-1 border rounded">キャンセル</button>
                    <button class="px-3 py-1 bg-green-600 text-white rounded">
                        送信
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
