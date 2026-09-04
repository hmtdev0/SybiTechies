<?php

use App\Http\Controllers\Admin\AboutCmsController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomScriptController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EditorUploadController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\EmailVerificationController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\HomeCmsController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobOpeningController;
use App\Http\Controllers\Admin\MediaFileController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SeoMetaController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Loaded under the `web` middleware group, `/admin` prefix and `admin.`
| name prefix (see bootstrap/app.php). Only login/logout are reachable
| while a guest; everything else requires ['auth', 'admin'].
|
| Every module route group below also carries a `permission:` gate — this
| is the coarse "can you enter this module at all" check (normally the
| module's "view X" permission). Finer-grained checks (create/edit/delete/
| publish) happen inside each controller via $this->authorize(). See
| RolesAndPermissionsSeeder for the full permission list.
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Verification lives under plain `auth` (not `admin`) so an unverified,
// otherwise-valid admin can still reach the notice/resend/verify actions —
// the `admin` middleware is what redirects them here in the first place.
Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('verify-email/resend', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // No permission gate beyond panel access — every role can log out, see
    // the dashboard, search, and manage their own profile.
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('search', [SearchController::class, 'index'])->name('search');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Rich-text editor inline image uploads (Service/Project/Blog Post
    // content fields) — gated the same as Media, since it's the same
    // underlying capability (uploading a file) used from inside a form.
    Route::middleware('permission:upload media')->group(function () {
        Route::post('editor-uploads', [EditorUploadController::class, 'store'])->name('editor-uploads.store');
    });

    // Services
    Route::middleware('permission:view services')->group(function () {
        Route::patch('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::delete('services/bulk-destroy', [ServiceController::class, 'bulkDestroy'])->name('services.bulk-destroy');
        Route::resource('services', ServiceController::class)->except('show');
    });

    // Portfolio (Projects)
    Route::middleware('permission:view portfolio')->group(function () {
        Route::patch('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::delete('projects/bulk-destroy', [ProjectController::class, 'bulkDestroy'])->name('projects.bulk-destroy');
        Route::delete('projects/{project}/images/{projectImage}', [ProjectController::class, 'destroyImage'])->name('projects.images.destroy');
        Route::resource('projects', ProjectController::class)->except('show');
    });

    // Blog Posts
    Route::middleware('permission:view blogs')->group(function () {
        Route::delete('blog-posts/bulk-destroy', [BlogPostController::class, 'bulkDestroy'])->name('blog-posts.bulk-destroy');
        Route::delete('blog-posts/{blogPost}/images/{blogPostImage}', [BlogPostController::class, 'destroyImage'])->name('blog-posts.images.destroy');
        Route::resource('blog-posts', BlogPostController::class)
            ->except('show')
            ->parameters(['blog-posts' => 'blogPost']);
    });

    // Testimonials
    Route::middleware('permission:view testimonials')->group(function () {
        Route::patch('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
        Route::delete('testimonials/bulk-destroy', [TestimonialController::class, 'bulkDestroy'])->name('testimonials.bulk-destroy');
        Route::resource('testimonials', TestimonialController::class)->except('show');
    });

    // Team Members
    Route::middleware('permission:view team')->group(function () {
        Route::patch('team/{team}/toggle-status', [TeamMemberController::class, 'toggleStatus'])->name('team.toggle-status');
        Route::delete('team/bulk-destroy', [TeamMemberController::class, 'bulkDestroy'])->name('team.bulk-destroy');
        Route::resource('team', TeamMemberController::class)->except('show');
    });

    // Contact Messages (Inquiries)
    Route::middleware('permission:view inquiries')->group(function () {
        Route::patch('messages/{message}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');
        Route::post('messages/{message}/reply', [ContactMessageController::class, 'reply'])->name('messages.reply');
        Route::delete('messages/bulk-destroy', [ContactMessageController::class, 'bulkDestroy'])->name('messages.bulk-destroy');
        Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
    });

    // Users
    Route::middleware('permission:view users')->group(function () {
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('users/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
        Route::resource('users', UserController::class)->except('show');
    });

    // Roles & Permissions — Super Admin only (nobody else is ever granted
    // "manage roles"; Super Admin reaches it via the Gate::before bypass).
    Route::middleware('permission:manage roles')->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    // Media Library — no separate "view media" permission exists, so
    // either capability is enough to browse the library.
    Route::middleware('permission:upload media|delete media')->group(function () {
        Route::delete('media/bulk-destroy', [MediaFileController::class, 'bulkDestroy'])->name('media.bulk-destroy');
        Route::get('media', [MediaFileController::class, 'index'])->name('media.index');
        Route::post('media', [MediaFileController::class, 'store'])->name('media.store');
        Route::delete('media/{mediaFile}', [MediaFileController::class, 'destroy'])->name('media.destroy');
    });

    // Everything else — Website Settings, Email Settings, SEO Manager,
    // Home/About Page CMS, and the smaller taxonomy/config modules
    // (Technologies, Project Categories, Blog Categories, Industries,
    // Process Steps, FAQs, Careers, Newsletter, Menu Manager) — are all
    // gated behind the single "manage settings" permission rather than a
    // separate permission per module. See RolesAndPermissionsSeeder's
    // "Adding a new permission" note if you want to split any of these out.
    Route::middleware('permission:manage settings')->group(function () {
        // Website Settings (singleton)
        Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');

        // Email Settings (singleton) — SMTP + contact-form auto-reply template
        Route::get('email-settings', [EmailSettingController::class, 'edit'])->name('email-settings.edit');
        Route::put('email-settings', [EmailSettingController::class, 'update'])->name('email-settings.update');
        Route::post('email-settings/send-test', [EmailSettingController::class, 'sendTest'])->name('email-settings.send-test');

        // SEO Manager (one entry per static page_key)
        Route::get('seo', [SeoMetaController::class, 'index'])->name('seo.index');
        Route::get('seo/{pageKey}', [SeoMetaController::class, 'edit'])->name('seo.edit');
        Route::put('seo/{pageKey}', [SeoMetaController::class, 'update'])->name('seo.update');

        // Home Page CMS
        Route::get('home-cms', [HomeCmsController::class, 'edit'])->name('home-cms.edit');
        Route::put('home-cms/hero', [HomeCmsController::class, 'updateHero'])->name('home-cms.hero.update');

        Route::post('home-cms/hero-stats', [HomeCmsController::class, 'storeHeroStat'])->name('home-cms.hero-stats.store');
        Route::put('home-cms/hero-stats/{heroStat}', [HomeCmsController::class, 'updateHeroStat'])->name('home-cms.hero-stats.update');
        Route::delete('home-cms/hero-stats/{heroStat}', [HomeCmsController::class, 'destroyHeroStat'])->name('home-cms.hero-stats.destroy');

        Route::post('home-cms/statistics', [HomeCmsController::class, 'storeStatistic'])->name('home-cms.statistics.store');
        Route::put('home-cms/statistics/{statistic}', [HomeCmsController::class, 'updateStatistic'])->name('home-cms.statistics.update');
        Route::delete('home-cms/statistics/{statistic}', [HomeCmsController::class, 'destroyStatistic'])->name('home-cms.statistics.destroy');

        Route::post('home-cms/trusted-companies', [HomeCmsController::class, 'storeTrustedCompany'])->name('home-cms.trusted-companies.store');
        Route::put('home-cms/trusted-companies/{trustedCompany}', [HomeCmsController::class, 'updateTrustedCompany'])->name('home-cms.trusted-companies.update');
        Route::delete('home-cms/trusted-companies/{trustedCompany}', [HomeCmsController::class, 'destroyTrustedCompany'])->name('home-cms.trusted-companies.destroy');

        Route::post('home-cms/why-us', [HomeCmsController::class, 'storeWhyUs'])->name('home-cms.why-us.store');
        Route::put('home-cms/why-us/{whyChooseUsItem}', [HomeCmsController::class, 'updateWhyUs'])->name('home-cms.why-us.update');
        Route::delete('home-cms/why-us/{whyChooseUsItem}', [HomeCmsController::class, 'destroyWhyUs'])->name('home-cms.why-us.destroy');

        // About Page CMS
        Route::get('about-cms', [AboutCmsController::class, 'edit'])->name('about-cms.edit');
        Route::put('about-cms', [AboutCmsController::class, 'update'])->name('about-cms.update');

        Route::post('about-cms/features', [AboutCmsController::class, 'storeFeature'])->name('about-cms.features.store');
        Route::put('about-cms/features/{aboutFeature}', [AboutCmsController::class, 'updateFeature'])->name('about-cms.features.update');
        Route::delete('about-cms/features/{aboutFeature}', [AboutCmsController::class, 'destroyFeature'])->name('about-cms.features.destroy');

        Route::post('about-cms/timeline', [AboutCmsController::class, 'storeTimeline'])->name('about-cms.timeline.store');
        Route::put('about-cms/timeline/{aboutTimeline}', [AboutCmsController::class, 'updateTimeline'])->name('about-cms.timeline.update');
        Route::delete('about-cms/timeline/{aboutTimeline}', [AboutCmsController::class, 'destroyTimeline'])->name('about-cms.timeline.destroy');

        // Technologies
        Route::patch('technologies/{technology}/toggle-status', [TechnologyController::class, 'toggleStatus'])->name('technologies.toggle-status');
        Route::delete('technologies/bulk-destroy', [TechnologyController::class, 'bulkDestroy'])->name('technologies.bulk-destroy');
        Route::resource('technologies', TechnologyController::class)->except('show');

        // Project Categories
        Route::patch('project-categories/{projectCategory}/toggle-status', [ProjectCategoryController::class, 'toggleStatus'])->name('project-categories.toggle-status');
        Route::delete('project-categories/bulk-destroy', [ProjectCategoryController::class, 'bulkDestroy'])->name('project-categories.bulk-destroy');
        Route::resource('project-categories', ProjectCategoryController::class)
            ->except('show')
            ->parameters(['project-categories' => 'projectCategory']);

        // Blog Categories
        Route::patch('blog-categories/{blogCategory}/toggle-status', [BlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle-status');
        Route::delete('blog-categories/bulk-destroy', [BlogCategoryController::class, 'bulkDestroy'])->name('blog-categories.bulk-destroy');
        Route::resource('blog-categories', BlogCategoryController::class)
            ->except('show')
            ->parameters(['blog-categories' => 'blogCategory']);

        // Industries
        Route::patch('industries/{industry}/toggle-status', [IndustryController::class, 'toggleStatus'])->name('industries.toggle-status');
        Route::delete('industries/bulk-destroy', [IndustryController::class, 'bulkDestroy'])->name('industries.bulk-destroy');
        Route::resource('industries', IndustryController::class)->except('show');

        // FAQs
        Route::patch('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
        Route::delete('faqs/bulk-destroy', [FaqController::class, 'bulkDestroy'])->name('faqs.bulk-destroy');
        Route::resource('faqs', FaqController::class)->except('show');

        // Careers — Job Openings
        Route::patch('job-openings/{jobOpening}/toggle-status', [JobOpeningController::class, 'toggleStatus'])->name('job-openings.toggle-status');
        Route::delete('job-openings/bulk-destroy', [JobOpeningController::class, 'bulkDestroy'])->name('job-openings.bulk-destroy');
        Route::resource('job-openings', JobOpeningController::class)
            ->except('show')
            ->parameters(['job-openings' => 'jobOpening']);

        // Careers — Job Applications (inbox)
        Route::patch('job-applications/{jobApplication}/toggle-read', [JobApplicationController::class, 'toggleRead'])->name('job-applications.toggle-read');
        Route::delete('job-applications/bulk-destroy', [JobApplicationController::class, 'bulkDestroy'])->name('job-applications.bulk-destroy');
        Route::get('job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
        Route::get('job-applications/{jobApplication}', [JobApplicationController::class, 'show'])->name('job-applications.show');
        Route::delete('job-applications/{jobApplication}', [JobApplicationController::class, 'destroy'])->name('job-applications.destroy');

        // Development Process
        Route::delete('process-steps/bulk-destroy', [ProcessStepController::class, 'bulkDestroy'])->name('process-steps.bulk-destroy');
        Route::resource('process-steps', ProcessStepController::class)
            ->except('show')
            ->parameters(['process-steps' => 'processStep']);

        // Newsletter
        Route::get('newsletter/export', [NewsletterController::class, 'export'])->name('newsletter.export');
        Route::delete('newsletter/bulk-destroy', [NewsletterController::class, 'bulkDestroy'])->name('newsletter.bulk-destroy');
        Route::get('newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
        Route::delete('newsletter/{subscriber}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');

        // Menu Manager
        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
        Route::post('menus/items', [MenuController::class, 'store'])->name('menus.items.store');
        Route::put('menus/items/{menuItem}', [MenuController::class, 'update'])->name('menus.items.update');
        Route::delete('menus/items/{menuItem}', [MenuController::class, 'destroy'])->name('menus.items.destroy');
        Route::patch('menus/items/{menuItem}/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.items.toggle-status');

        // Custom Scripts — injected into the public site's <head> and before
        // </body> by resources/views/layouts/frontend.blade.php.
        Route::patch('custom-scripts/{customScript}/toggle-status', [CustomScriptController::class, 'toggleStatus'])->name('custom-scripts.toggle-status');
        Route::delete('custom-scripts/bulk-destroy', [CustomScriptController::class, 'bulkDestroy'])->name('custom-scripts.bulk-destroy');
        Route::resource('custom-scripts', CustomScriptController::class)
            ->except('show')
            ->parameters(['custom-scripts' => 'customScript']);
    });
});
