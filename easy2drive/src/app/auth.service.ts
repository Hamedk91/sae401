import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = 'http://localhost/sae401/admin_api.php'; 

  constructor(private http: HttpClient, private router: Router) {}

  login(email: string, password: string) {
    const body = {
      email: email,
      mot_de_passe: password
    };
    return this.http.post<any>(`${this.apiUrl}/login`, body);
  }

  logout() {
    localStorage.removeItem('token');
    this.router.navigate(['/login']);
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem('token');
  }
}