import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  standalone: false
})
export class LoginComponent {
  email: string = '';
  password: string = '';

  constructor(private authService: AuthService, private router: Router) {}

  login() {
    this.authService.login(this.email, this.password).subscribe(
      (response) => {
        if (response) {
          localStorage.setItem('token', 'dummy-token'); // Stockez un token factice (à remplacer par un vrai token)
          this.router.navigate(['/students']); // Redirigez vers la liste des étudiants
        }
      },
      (error) => {
        console.error('Erreur de connexion', error);
      }
    );
  }
}