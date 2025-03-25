import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

interface Student {
  id_eleve: number;
  nom: string;
  prenom: string;
  date_naissance: string;
  rue: string;
  code_postal: string;
  ville: string;
  NPEH: string;
  id_auto_ecole: number;
}

interface Test {
  id_test: number;
  theme: string;
  date: string;
  score: number;
  id_admin: number;
  id_eleve: number;
}

interface ApiResponse {
  success: boolean;
  message?: string;
  [key: string]: any;
}

@Injectable({
  providedIn: 'root'
})
export class StudentService {
  private apiUrl = 'http://localhost/sae401/admin_api.php';

  constructor(private http: HttpClient) {}

  // Student operations
  getStudents(): Observable<{success: boolean; eleves: Student[]}> {
    return this.http.get<{success: boolean; eleves: Student[]}>(`${this.apiUrl}?students=true`);
  }

  getStudentById(id: number): Observable<{success: boolean; eleve: Student}> {
    return this.http.get<{success: boolean; eleve: Student}>(`${this.apiUrl}?student=true&id_eleve=${id}`);
  }

  addStudent(student: Omit<Student, 'id_eleve'>): Observable<ApiResponse> {
    return this.http.post<ApiResponse>(`${this.apiUrl}?ajouter=true`, student);
  }

  updateStudent(student: Student): Observable<ApiResponse> {
    return this.http.put<ApiResponse>(`${this.apiUrl}?modifier_eleve=true`, student);
  }

  deleteStudent(id: number): Observable<ApiResponse> {
    return this.http.delete<ApiResponse>(`${this.apiUrl}?supprimer=true`, {
      body: { id_eleve: id }
    });
  }

  // Test operations
  getTests(): Observable<{success: boolean; tests: Test[]}> {
    return this.http.get<{success: boolean; tests: Test[]}>(`${this.apiUrl}?tests=true`);
  }

  getTestById(id: number): Observable<{success: boolean; test: Test}> {
    return this.http.get<{success: boolean; test: Test}>(`${this.apiUrl}?test=true&id_test=${id}`);
  }

  

  updateTest(test: Test): Observable<ApiResponse> {
    return this.http.put<ApiResponse>(`${this.apiUrl}?modifier_test=true`, test);
  }

  deleteTest(id: number): Observable<ApiResponse> {
    return this.http.delete<ApiResponse>(`${this.apiUrl}?supprimer_test=true`, {
      body: { id_test: id }
    });
  }
  addTest(test: Omit<Test, 'id_test'>): Observable<ApiResponse> {
    // Ajoutez des logs pour débogage
    console.log('Données envoyées:', test);
    
    return this.http.post<ApiResponse>(`${this.apiUrl}`, {
      ajouter_test: true,
      ...test
    });
  }
}