<x-tmk.section class="block mt-1 mb-2">
    @if ($questionnaire == null)
        <x-tmk.form.alert type="danger" class="w-full">
            {{ __('questionnaire.noQuestionnaire') }}
        </x-tmk.form.alert>
    @else
        <iframe src="{{ $questionnaire->url }}" width="100%" height="100vh" frameborder="0" marginheight="0"
            marginwidth="0">{{ __('crud.loading') }}</iframe>
    @endif
</x-tmk.section>
