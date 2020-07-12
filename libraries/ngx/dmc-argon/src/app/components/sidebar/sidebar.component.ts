import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {AuthService} from "../../security/auth.service";
import {Role} from "../../security/role.service";

declare interface RouteInfo {
  path: string;
  title: string;
  icon: string;
  class: string;
  role: Role;
  children: RouteInfo[];
}

export const ROUTES: RouteInfo[] = [
  // {path: '/dashboard', title: 'Dashboard', icon: 'ni-tv-2 text-primary', class: '', children: []},
  // {path: '/icons', title: 'Icons', icon: 'ni-planet text-blue', class: '', children: []},
  {
    path: '#', title: 'Tele-Wellness', icon: 'ni-planet text-blue', class: '', role: Role.SUPERVISOR_TELEMED, children: [
      {
        path: '/supervisor/telemed/create',
        title: 'Create Telemed Chit',
        icon: 'ni-fat-add text-yellow',
        class: '',
        role: null,
        children: []
      },
      {
        path: '/supervisor/telemed/list',
        title: 'Manage Telemed Chit',
        icon: 'ni-bullet-list-67 text-red',
        class: '',
        role: null,
        children: []
      },

    ]
  },
  {
    path: '#', title: 'Medical Chit', icon: 'ni-planet text-blue', class: '', role: Role.SUPERVISOR_DMC, children: [
      {
        path: '/supervisor/dmc/create',
        title: 'Create Digital Medical Chit',
        icon: 'ni-fat-add text-yellow',
        class: '',
        role: null,
        children: []
      },
      {
        path: '/supervisor/dmc/list',
        title: 'Manage Digital Medical Chit',
        icon: 'ni-bullet-list-67 text-red',
        class: '',
        role: null,
        children: []
      },

    ]
  },
  {
    path: '#', title: 'Centre', icon: 'ni-planet text-blue', class: '', role: Role.MERCHANT_USER, children: [
      // {path: '/merchant/login', title: 'Login', icon: 'ni-single-02 text-yellow', class: '', role: null, children: []},
      {
        path: '/merchant/dmc/list',
        title: 'Redeem Digital Medical Chit',
        icon: 'ni-check-bold text-red',
        class: '',
        role: null,
        children: []
      },
      // {path: '/supervisor/dmc/redeem', title: 'Create Digital Medical Chit', icon: 'ni-fat-add text-yellow', class: '', children: []},
    ]
  },
  // {path: '/maps', title: 'Maps', icon: 'ni-pin-3 text-orange', class: '', children: []},
  // {path: '/user-profile', title: 'User profile', icon: 'ni-single-02 text-yellow', class: '', children: []},
  // {path: '/tables', title: 'Tables', icon: 'ni-bullet-list-67 text-red', class: '', children: []},
  {path: '/logout', title: 'Logout', icon: 'ni-button-power text-info', class: '', role: null, children: []},
  // {path: '/register', title: 'Register', icon: 'ni-circle-08 text-pink', class: '', children: []}
];

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

  public menuItems: any[];
  public isCollapsed = true;

  constructor(private router: Router, private authService: AuthService) {
  }

  ngOnInit() {
    this.menuItems = ROUTES.filter(menuItem => {
      if (menuItem.role !== null) {
        if (this.authService.isGranted(menuItem.role)) {
          return menuItem;
        } else {
          return null;
        }
      }
      return menuItem;
    });
    this.router.events.subscribe((event) => {
      this.isCollapsed = true;
    });
  }

  getEntityName() {
    return 'Digital Medical Chit';
  }
}
