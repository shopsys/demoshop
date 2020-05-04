/**
 * Responsive tabs component that uses HybridTabs component to show
 * single tab mode (classic tabs) in desktop view and multiple tabs mode
 * (aka. accordion) in mobile view.
 *
 * @see Shopsys.HybridTabs
 *
 * == Notes ==
 * - There must be at least one "js-tab-button" for each "js-tab-content".
 * - You must hide desktop buttons in mobile view and mobile buttons in desktop
 *   view using CSS.
 *
 * == Example ==
 * === HTML mark-up ===
 * <div class="js-responsive-tabs">
 *     <a href="#" class="js-tab-button desktop-button" data-tab-id="content-a"></a>
 *     <a href="#" class="js-tab-button desktop-button" data-tab-id="content-b"></a>
 *
 *     <a href="#" class="js-tab-button mobile-button" data-tab-id="content-a"></a>
 *     <div class="js-tab-content" data-tab-id="content-a"></div>
 *
 *     <a href="#" class="js-tab-button mobile-button" data-tab-id="content-b"></a>
 *     <div class="js-tab-content" data-tab-id="content-b"></div>
 * </div>
 *
 * === LESS ===
 * @media @query-lg {
 *     .desktop-button {
 *         display: none;
 *     }
 * }
 * @media @query-xl {
 *     .mobile-button {
 *         display: none;
 *     }
 * }
 *
 * === JavaScript ===
 * There is no need to initialize the component in JavaScript.
 * It is automatically initialized on all DOM containers with class "js-responsive-tabs".
 */

(function ($) {
    Shopsys = window.Shopsys || {};

    Shopsys.register.registerCallback(function ($container) {
        $container.filterAllNodes('.js-responsive-tabs').each(function () {
            var hybridTabs = new Shopsys.hybridTabs.HybridTabs($(this));
            hybridTabs.init(getHybridTabsModeForCurrentResponsiveMode());

            Shopsys.responsive.registerOnLayoutChange(function () {
                hybridTabs.setTabsMode(getHybridTabsModeForCurrentResponsiveMode());
            });

            function getHybridTabsModeForCurrentResponsiveMode () {
                if (Shopsys.responsive.isDesktopVersion()) {
                    return Shopsys.hybridTabs.TABS_MODE_SINGLE;
                } else {
                    return Shopsys.hybridTabs.TABS_MODE_MULTIPLE;
                }
            }
        });
    });

})(jQuery);
