import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Add a response interceptor to handle 429 Too Many Requests
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response && error.response.status === 429) {
            window.showRateLimitPopup();
        }
        return Promise.reject(error);
    },
);

window.showRateLimitPopup = function () {
    // Check if popup already exists
    if (document.getElementById("rate-limit-popup")) return;

    const popupHtml = `
        <div id="rate-limit-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-50/80 dark:bg-black/80 backdrop-blur-md p-4 transition-opacity duration-300 font-mono">
            <div class="w-full max-w-md transform overflow-hidden rounded-lg bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 shadow-2xl transition-all">
                
                <!-- Terminal Header -->
                <div class="flex items-center px-4 py-2 bg-zinc-100 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-red-500/20 border border-red-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/20 border border-yellow-500/50"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/20 border border-green-500/50"></div>
                    </div>
                    <div class="mx-auto text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                        error_429.sh
                    </div>
                </div>

                <!-- Terminal Body -->
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 mt-1">
                            <span class="text-red-500 font-bold text-xl">!</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100 mb-2">
                                <span class="text-red-500">ERR_TOO_MANY_REQUESTS</span>
                            </h3>
                            <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <p>> System overload detected.</p>
                                <p>> You're sending requests faster than my server can brew coffee ☕</p>
                                <p class="text-zinc-500 dark:text-zinc-500 text-xs mt-4">
                                    <span class="text-green-500">➜</span> Action required: Take a deep breath, wait a few minutes, and try again.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button id="close-rate-limit-popup" class="group flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-900 dark:text-zinc-100 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-900 dark:hover:bg-zinc-800 border border-zinc-200 dark:border-zinc-800 rounded transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-950">
                            <span class="text-zinc-400 group-hover:text-zinc-500 transition-colors">./</span>
                            acknowledge.sh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", popupHtml);

    document
        .getElementById("close-rate-limit-popup")
        .addEventListener("click", () => {
            const popup = document.getElementById("rate-limit-popup");
            if (popup) {
                popup.remove();
            }
        });
};
