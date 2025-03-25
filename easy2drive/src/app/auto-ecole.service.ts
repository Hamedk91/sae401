// auto-ecole.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';


// Exportez explicitement les interfaces
export interface AutoEcole {
  id_auto_ecole: number;
  nom: string;
  adresse: string;
  telephone: string;
  email: string;
}

export interface ApiResponse<T = any> {
  success: boolean;
  message?: string;
  data?: T;
}

@Injectable({
  providedIn: 'root'
})
export class AutoEcoleService {
  private apiUrl = 'http://localhost/sae401/admin_api.php'; // Adaptez l'URL

  constructor(private http: HttpClient) { }


  getAutoEcoles(): Observable<ApiResponse<AutoEcole[]>> {
    return this.http.get<ApiResponse<AutoEcole[]>>(`${this.apiUrl}?auto_ecoles=true`);
  }

  getAutoEcole(id: number): Observable<ApiResponse<AutoEcole>> {
    return this.http.get<ApiResponse<AutoEcole>>(`${this.apiUrl}?auto_ecole=true&id=${id}`);
  }

  addAutoEcole(autoEcole: Omit<AutoEcole, 'id_auto_ecole'>): Observable<ApiResponse<number>> {
    return this.http.post<ApiResponse<number>>(`${this.apiUrl}?add_auto_ecole=true`, autoEcole);
  }

  updateAutoEcole(autoEcole: AutoEcole): Observable<ApiResponse<void>> {
    return this.http.put<ApiResponse<void>>(`${this.apiUrl}?update_auto_ecole=true`, autoEcole);
  }

  deleteAutoEcole(id: number): Observable<ApiResponse<void>> {
    return this.http.delete<ApiResponse<void>>(`${this.apiUrl}?delete_auto_ecole=true&id=${id}`);
  }
}

