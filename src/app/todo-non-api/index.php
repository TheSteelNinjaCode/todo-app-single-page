<?php

use Lib\Prisma\Classes\Prisma;

$prisma = new Prisma();

if ($isPost && isset($params->title)) {
    $todo = $prisma->todo->create([
        'data' => [
            'title' => $params->title,
        ],
    ]);
    renderTodos();
    exit;
}

function todos(string $search = '')
{
    global $prisma;
    return $prisma->todo->findMany([
        'where' => [
            'title' => [
                'contains' => $search
            ]
        ]
    ], true);
}

if ($isGet && isset($params->search)) {
    renderTodos($params->search);
    exit;
}

if ($isPut) {
    $todo = $prisma->todo->update([
        'where' => [
            'id' => $params->id
        ],
        'data' => [
            'title' => $params->title
        ]
    ]);
    renderTodos();
    exit;
}

if ($isPatch) {
    $todo = $prisma->todo->update([
        'where' => [
            'id' => $params->id
        ],
        'data' => [
            'completed' => isset($params->completed) ? true : false
        ]
    ]);
    renderTodos();
    exit;
}

if ($isDelete) {
    $todo = $prisma->todo->delete([
        'where' => [
            'id' => $params->id
        ]
    ]);
    renderTodos();
    exit;
}

if ($isGet && isset($params->count)) {
    echo 'Total: ' . $prisma->todo->count();
    exit;
}

if ($isGet && isset($params->completedCount)) {
    $completed = $prisma->todo->count([
        'where' => [
            'completed' => true
        ]
    ])['*'];

    $notCompleted = $prisma->todo->count([
        'where' => [
            'completed' => false
        ]
    ])['*'];

    echo "Completed: $completed / $notCompleted";
    exit;
}

?>

<div class="flex flex-col items-center justify-center h-screen bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex gap-4 justify-between mb-4 items-center w-full">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Todo List</h1>
            <input id="search-todos" placeholder="Search todos..." class="px-4 p-2 rounded-md bg-gray-100 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" name="search" hx-get="<?= $pathname ?>" hx-trigger="keyup delay:500ms changed" hx-target="#todos" />
        </div>
        <form id="edit-form" class="items-center mb-4 hidden" hx-put="<?= $pathname ?>" hx-target="#todos" hx-form="afterRequest:reset">
            <input type="hidden" name="id" id="edit-id" />
            <input id="edit-title" type="text" placeholder="Update todo..." class="flex-1 px-4 py-2 rounded-l-md bg-gray-100 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" name="title" />
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-r-md">Edit</button>
        </form>
        <form id="create-form" class="flex items-center mb-4" hx-post="<?= $pathname ?>" hx-target="#todos" hx-form="afterRequest:reset">
            <input type="text" placeholder="Add a new todo..." class="flex-1 px-4 py-2 rounded-l-md bg-gray-100 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500" name="title" />
            <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-r-md">Add</button>
        </form>
        <div class="space-y-2" id="todos" hx-get="<?= $pathname . '?search=""' ?>" hx-trigger="load">
            <?php function renderTodos(string $search = '')
            {
                global $pathname;
                header('HX-Trigger: todoUpdated');
                // to send multiple headers
                // header('HX-Trigger: ' . json_encode([
                //     'searchTodos' => null,
                //     'todoUpdated' => null
                // ]));
                foreach (todos($search) as $todo) : ?>
                    <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-700 rounded-md p-2">
                        <div class="flex items-center">
                            <input id="completed" type="checkbox" class="mr-2 text-blue-500 focus:ring-blue-500 focus:ring-2 rounded" <?= $todo->completed ? 'checked' : '' ?> name="completed" hx-patch="<?= $pathname ?>" hx-vals='{"id":"<?= $todo->id ?>"}' hx-target="#todos" />
                            <span class="<?= $todo->completed ? 'line-through' : '' ?> text-gray-500 dark:text-gray-400"><?= $todo->title ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="text-yellow-500 hover:text-yellow-600" hx-vals='{"targets": [{"id": "edit-title", "value": "<?= $todo->title ?>"}, 
                                {"id": "edit-id", "value": "<?= $todo->id ?>"}], 
                                "attributes": [{"id": "create-form", "attributes": {"class": "-flex hidden"}}, 
                                {"id": "edit-form", "attributes": {"class": "-hidden flex"}}], 
                                "swaps": [{"id": "create-form", "attributes": {"class": "-hidden flex"}}, 
                                {"id": "edit-form", "attributes": {"class": "-flex hidden"}}]}' hx-trigger="click">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                    <path d="M20 5H9l-7 7 7 7h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Z"></path>
                                    <line x1="18" x2="12" y1="9" y2="15"></line>
                                    <line x1="12" x2="18" y1="9" y2="15"></line>
                                </svg>
                            </button>
                            <button class="text-red-500 hover:text-red-600" hx-delete="<?= $pathname ?>" hx-vals='{"id":"<?= $todo->id ?>"}' hx-target="#todos">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
            <?php endforeach;
            } ?>
        </div>
        <div class="text-gray-500 pt-2 flex justify-between">
            <span hx-get="<?= $pathname . '?count=""' ?>" hx-trigger="load, todoUpdated from:body"></span>
            <span hx-get="<?= $pathname . '?completedCount=""' ?>" hx-trigger="load, todoUpdated from:body"></span>
        </div>
    </div>
</div>