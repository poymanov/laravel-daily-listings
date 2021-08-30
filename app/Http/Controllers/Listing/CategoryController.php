<?php

declare(strict_types=1);

namespace App\Http\Controllers\Listing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Listing\Category\UpdateRequest;
use App\Models\Listing;
use App\Service\CategoryService;
use App\UseCase\Listing\Category\Update;
use Throwable;

class CategoryController extends Controller
{
    /** @var CategoryService */
    private CategoryService $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @param Listing $listing
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $categories           = $this->categoryService->findAll();
        $listingCategoriesIds = $this->categoryService->getCategoryIdsByListingAsArray($listing);

        return view('listings.categories.edit', compact('listing', 'categories', 'listingCategoriesIds'));
    }

    /**
     * @param UpdateRequest $request
     * @param Listing       $listing
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $command = new Update\Command($listing->id, $request->get('categories'));

        try {
            $handler = new Update\Handler();
            $handler->handle($command);

            return redirect()->route('listings.edit', $listing)->with('alert.success', 'Categories updated');
        } catch (Throwable $e) {
            return redirect()->back()->with('alert.error', 'Failed to update category');
        }
    }
}
