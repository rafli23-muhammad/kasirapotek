<!DOCTYPE html>
<html lang="en" class="dark">

<x-header></x-header>

<body 
  x-data="{
    page: 'ecommerce',
    loaded: true,
    darkMode: true,
    stickyMenu: false,
    sidebarToggle: false,
    scrollTop: false,
    initLoading() {
      const finishLoading = () => {
        setTimeout(() => { this.loaded = false }, 150)
      }

      if (document.readyState === 'loading') {
        window.addEventListener('DOMContentLoaded', finishLoading, { once: true })
      } else {
        finishLoading()
      }

      window.addEventListener('pageshow', finishLoading)

      document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]')

        if (!link || event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
          return
        }

        const href = link.getAttribute('href')

        if (!href || href.startsWith('#') || link.target === '_blank' || link.hasAttribute('download')) {
          return
        }

        if (new URL(link.href, window.location.href).origin === window.location.origin) {
          this.loaded = true
        }
      }, true)
    }
  }"
  x-init="initLoading()"
  :class="{ 'dark text-bodydark ': darkMode === true }">

  <div x-show="loaded" x-cloak
       class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
  </div>

  <div class="flex h-screen w-screen overflow-hidden">
    @if (!request()->routeIs('login'))
      <x-sidebar></x-sidebar>
    @endif

    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden w-full">
      @if (!request()->routeIs('login'))
        <x-navbar></x-navbar>
      @endif
      {{ $slot }}
    </div>
  </div>

  <script defer src="{{ asset('js/bundle.js') }}"></script>
  @stack('scripts')

  @if (session('toast_error'))
    <script>
      Swal.fire({
        toast: true,
        icon: 'error',
        title: '{{ session('toast_error') }}',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    </script>
  @endif

  @if (session('toast_success'))
    <script>
      Swal.fire({
        toast: true,
        icon: 'success',
        title: '{{ session('toast_success') }}',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    </script>
  @endif
</body>

</html>
