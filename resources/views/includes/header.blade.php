<!-- Navbar -->
<nav class="nav-main bg-primary fixed top-0 left-0 right-0 z-50">
  <div class="flex items-center justify-between px-4">
    <a class="flex items-center text-white no-underline" href="/">
      <i class="fa fa-server mr-2"></i>
      <span>HackGreenville</span>
    </a>

    <!--This creates and controls a dropdown menu on small screens -->
    <button class="nav-toggler lg:hidden text-white" type="button" onclick="toggleHeaderMenu()" aria-controls="headerNavContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="nav-toggler-icon"></span>
    </button>

    <div class="nav-collapse hidden lg:flex justify-end" id="headerNavContent">
      <ul class="nav-menu flex items-center gap-4">
        <li>
          <a class="text-white hover:text-gray-200 no-underline" href="/orgs">
            <i class="fa fa-users mr-1"></i>
            <span>Organizations</span>
          </a>
        </li>

        <li>
          <a class="text-white hover:text-gray-200 no-underline" title="Sign up for the HackGreenville Slack" href="/join-slack">
            <i class="fa fa-slack mr-1"></i>
            <span>Join Slack</span>
          </a>
        </li>

        <li>
          <a class="text-white hover:text-gray-200 no-underline" title="Login to the HackGreenville Slack" href="https://hackgreenville.slack.com">
            <i class="fa fa-slack mr-1"></i>
            <span>Slack Login</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
function toggleHeaderMenu() {
    const menu = document.getElementById('headerNavContent');
    menu.classList.toggle('hidden');
}
</script>

<!-- End Navbar -->
