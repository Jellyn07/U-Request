<header class="fixed top-0 left-0 w-full text-text flex items-center justify-end">
  <button id="profile-btn" type="button" class="flex items-center">
    <div class="flex items-center mr-5 px-5 py-5 first-letter:">
      <img 
        src="<?php echo htmlspecialchars(!empty($profile['profile_pic']) ? $profile['profile_pic'] : '/public/assets/img/user-default.png'); ?>" 
        alt="user profile" 
        class="w-9 h-9 rounded-full object-cover border border-secondary shadow-sm mr-3"/>
      <span>
        Admin Name
      </span>
    </div>
  </button>
</header>