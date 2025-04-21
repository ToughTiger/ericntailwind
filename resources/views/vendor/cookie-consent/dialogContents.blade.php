<!-- <div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-4 md:p-2 rounded-lg bg-yellow-100">
            <div class="flex items-center justify-between flex-wrap">
                <div class="max-w-full flex-1 items-center md:w-0 md:inline">
                    <p class="md:ml-3 text-black cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                    <button class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-yellow-800 bg-yellow-400 hover:bg-yellow-300">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="fixed bottom-0 left-0 right-0 bg-gray-800 text-white p-4 z-50" id="cookieConsentModal">
  <div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
      <p class="text-sm md:text-base">
        We use cookies to enhance your experience, analyze site usage, and provide tailored marketing efforts. 
        You can manage your preferences or consent to the use of all cookies by clicking "Accept All". 
        For more detailed information, see our 
        <a href="/privacy" class="text-blue-300 hover:text-blue-200 underline">Privacy Policy</a> or 
        <a href="/cookies" class="text-blue-300 hover:text-blue-200 underline">Cookie Policy</a>.
      </p>
      <div class="flex gap-2 flex-shrink-0 mt-2">
        <button 
          onclick="acceptAllCookies()"
          class="px-4 py-2 bg-blue-600 hover:bg-blue-800 rounded text-sm font-medium transition-colors mr-3"
        >
          Accept All
        </button>
        <button 
          onclick="document.getElementById('cookiePreferencesModal').classList.remove('hidden')"
          class="px-4 py-2 bg-blue-600 hover:bg-blue-800 rounded text-sm font-medium transition-colors"
        >
          Manage Preferences
        </button>
      </div>
    </div>
  </div>

  <!-- Modal for managing preferences -->
  <div id="cookiePreferencesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-start p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white">Manage Cookie Preferences</h3>
          <button 
            onclick="document.getElementById('cookiePreferencesModal').classList.add('hidden')"
            class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <p class="text-gray-600 dark:text-gray-300 mb-6">
          Select which cookies you want to allow:
        </p>

        <div class="space-y-6">
          <!-- Necessary Cookies -->
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-start">
              <div class="flex items-center h-5 mr-3">
                <input 
                  type="checkbox" 
                  id="necessaryCookie" 
                  checked 
                  disabled
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3">
                <label for="necessaryCookie" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Necessary Cookies
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  These cookies are essential for the website to function and cannot be switched off.
                </p>
              </div>
            </div>
          </div>

          <!-- Analytics Cookies -->
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-start">
              <div class="flex items-center h-5 mr-3">
                <input 
                  type="checkbox" 
                  id="analyticsCookies"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3">
                <label for="analyticsCookies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Analytics Cookies
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  These cookies help us understand how visitors interact with our website by collecting information anonymously.
                </p>
              </div>
            </div>
          </div>

          <!-- Marketing Cookies -->
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-start">
              <div class="flex items-center h-5 mr-3">
                <input 
                  type="checkbox" 
                  id="marketingCookies"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3">
                <label for="marketingCookies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Marketing Cookies
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  We use marketing cookies to track visitors across websites to display relevant advertisements.
                </p>
              </div>
            </div>
          </div>

          <!-- Google Analytics -->
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-start">
              <div class="flex items-center h-5 mr-3">
                <input 
                  type="checkbox" 
                  id="googleAnalyticsCookies"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3">
                <label for="googleAnalyticsCookies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Google Analytics
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  We use Google Analytics to track visitors across websites to display relevant advertisements.
                </p>
              </div>
            </div>
          </div>

          <!-- Facebook Pixel -->
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-start">
              <div class="flex items-center h-5 mr-3">
                <input 
                  type="checkbox" 
                  id="facebookCookies"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3">
                <label for="facebookCookies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Facebook Pixel
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  We use Facebook Pixel to track visitors across websites to display relevant advertisements.
                </p>
              </div>
            </div>
          </div>

          <!-- Twitter Pixel -->
          <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <div class="flex items-start">
              <div class="flex items-center h-5 mr-3">
                <input 
                  type="checkbox" 
                  id="twitterCookies"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3">
                <label for="twitterCookies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  X Pixel
                </label>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                  We use X Pixel to track visitors across websites to display relevant advertisements.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button 
            onclick="document.getElementById('cookiePreferencesModal').classList.add('hidden')"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded text-sm font-medium transition-colors"
          >
            Cancel
          </button>
          <button 
            onclick="saveCookiePreferences()"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-sm font-medium text-white transition-colors"
          >
            Save Preferences
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Your existing JavaScript remains the same
  document.addEventListener('DOMContentLoaded', function() {
    checkCookieConsent();
  });

  function acceptAllCookies() {
    // Set all cookies to 'accepted'
    setCookie('marketing_cookies', 'accepted', 365);
    setCookie('facebook_cookies', 'accepted', 365);
    setCookie('twitter_cookies', 'accepted', 365);
    setCookie('google_analytics_cookies', 'accepted', 365);
    setCookie('analytics_cookies', 'accepted', 365);

    // Store a general flag to remember that user accepted all cookies
    setCookie('cookies_accepted', 'true', 365);

    // Hide the cookie consent modal
    document.getElementById('cookieConsentModal').style.display = 'none';
  }

  function saveCookiePreferences() {
    const preferences = {
      marketing: document.getElementById('marketingCookies').checked ? 'accepted' : 'denied',
      facebook: document.getElementById('facebookCookies').checked ? 'accepted' : 'denied',
      twitter: document.getElementById('twitterCookies').checked ? 'accepted' : 'denied',
      google_analytics: document.getElementById('googleAnalyticsCookies').checked ? 'accepted' : 'denied',
      analytics: document.getElementById('analyticsCookies').checked ? 'accepted' : 'denied'
    };

    // Set individual cookies
    Object.entries(preferences).forEach(([key, value]) => {
      setCookie(`${key}_cookies`, value, 365);
    });

    // Set general consent flag
    setCookie('cookies_accepted', 'true', 365);

    // Hide modals
    document.getElementById('cookiePreferencesModal').classList.add('hidden');
    document.getElementById('cookieConsentModal').style.display = 'none';
  }

  function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
  }

  function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

  function checkCookieConsent() {
    const modal = document.getElementById('cookieConsentModal');
    if (!modal) return;
    
    const cookiesAccepted = getCookie('cookies_accepted');
    if (cookiesAccepted === 'true') {
      modal.style.display = 'none';
    } else {
      modal.style.display = 'block';
    }
  }
</script>
