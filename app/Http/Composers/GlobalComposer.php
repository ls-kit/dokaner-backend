<?php

namespace App\Http\Composers;

use Illuminate\View\View;

/**
 * Class GlobalComposer.
 */
class GlobalComposer
{
  /**
   * Bind data to the view.
   *
   * @param View $view
   */
  public function compose(View $view)
  {
    $view->with([
      'logged_in_user' => auth()->user(),
      'productLoader' => get_setting('product_image_loader'),
      'currency' => get_setting('currency_icon'),
    ]);
  }
}
