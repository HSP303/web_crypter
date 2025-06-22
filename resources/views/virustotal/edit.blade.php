<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a Test') }}
        </h2>
    </x-slot>

    <div class="py-12 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('test.update', [$test['id']]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <label class="block text-lg font-medium text-gray-700 mb-4">
                            Test Description
                        </label>
                        
                        <div class="space-y-2">
                            <div class="sm:col-span-3">
                                <input 
                                    type="text" 
                                    name="description" 
                                    id="description" 
                                    autocomplete="given-name" 
                                    placeholder="Description..."
                                    value="{{ $test['description'] }}"
                                    class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent sm:text-sm/6" 
                                />
                            </div>
                            <br>
                            
                        <label class="block text-lg font-medium text-gray-700 mb-4">
                            File
                        </label>
                        
                        <div class="space-y-2">
                            
                            <div class="sm:col-span-3">
                                <input 
                                    type="text" 
                                    name="file" 
                                    id="file" 
                                    disabled
                                    value="{{ $file['filename'] }}"
                                    class="block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-base text-gray-900 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent sm:text-sm/6" 
                                />
                            </div>

                            <br>
                            <br>
                            
                            <div class="flex justify-end space-x-4">
                                <button type="reset" class="text-sm/6 font-semibold text-gray-900">Cancel</button>
                                <button type="submit" class="rounded-md bg-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-violet-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-600">Edit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
