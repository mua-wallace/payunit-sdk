<form action="/makePayment" method="post" class="mb-4">
  @csrf
  <div class="mb-4">
    <label for="body" class="sr-only">Body</label>
    <input name="total_amount" id="body" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('body') border-red-500 @enderror" placeholder="Donate something for poor people!">
    @error('body')
    <div class="text-red-500 mt-2 text-sm">
      {{ $message }}
    </div>
    @enderror
  </div>
  <div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded font-medium">Donate FCFA</button>
  </div>
</form>