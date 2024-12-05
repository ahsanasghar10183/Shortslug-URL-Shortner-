 <!-- partial:partials/_sidebar.html -->
  <?php $baseurl ?>
 <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $baseUrl?>">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Analytics</span>
              </a>
            </li>
            <li class="nav-item nav-category">Tools</li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Create New Link</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $baseUrl; ?>links/create_short_link.php">Custom Short Link</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $baseUrl; ?>links/create_email_link.php">Email Link</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $baseUrl; ?>links/create_sms_link.php">SMS Link</a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $baseUrl; ?>links/create_tel_link.php">Telephone Link</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $baseUrl?>links/view_all_links.php">
                <i class="menu-icon mdi mdi-card-text-outline"></i>
                <span class="menu-title">Manage Links</span>
           
              </a>
              
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <i class="menu-icon mdi mdi-table"></i>
                <span class="menu-title">Browser Plugins</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Chrome Extension</a></li>
                </ul>
              </div>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Firefox Extension</a></li>
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link"  href="<?php echo $baseUrl?>links/bulk_import.php">
                <i class="menu-icon mdi mdi-layers-outline"></i>
                <span class="menu-title">Bulk Import</span>
              </a>
              
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">Landing Pages</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link"  href="<?php echo $baseUrl; ?>landingpages/create.php"> Create Landing Page </a></li>
                  <li class="nav-item"> <a class="nav-link" href="<?php echo $baseUrl; ?>landingpages/view.php"> View Landing Pages </a></li>
                
                </ul>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="docs/documentation.html">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Documentation</span>
              </a>
            </li>
          </ul>
        </nav>