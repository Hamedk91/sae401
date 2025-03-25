// src/app/types.ts
export interface Student {
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
  
  export interface Test {
    id_test: number;
    theme: string;
    date: string;
    score: number;
    id_admin: number;
    id_eleve: number;
  }
  
  export interface ApiResponse<T = void> {
    success: boolean;
    message?: string;
    data?: T;
  }
  
  export interface StudentsResponse extends ApiResponse<Student[]> {
    eleves: Student[];
  }
  
  export interface TestsResponse extends ApiResponse<Test[]> {
    tests: Test[];
  }