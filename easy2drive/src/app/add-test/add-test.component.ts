import { Component } from '@angular/core';
import { StudentService } from '../student.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-add-test',
  templateUrl: './add-test.component.html',
  styleUrls: ['./add-test.component.css'],
  standalone: false
})
export class AddTestComponent {
  test = {
    theme: '', 
    date: '', 
    score: 0,
    id_admin: 0,  // ID de l'admin qui crée le test
    id_eleve: 0   // ID de l'élève concerné
  };
  isSubmitting = false;
  errorMessage = '';

  constructor(
    private studentService: StudentService,
    private router: Router
  ) {}

  submitTest(): void {
    if (this.isSubmitting) return;
    
    // Validation des données
    if (!this.test.theme || !this.test.date || this.test.id_eleve <= 0 || this.test.id_admin <= 0) {
      this.errorMessage = 'Veuillez remplir tous les champs obligatoires';
      return;
    }

    this.isSubmitting = true;
    this.errorMessage = '';
    
    this.studentService.addTest(this.test).subscribe({
      next: (response) => {
        console.log('Réponse du serveur:', response);
        if (response.success) {
          this.router.navigate(['/tests']);
        } else {
          this.errorMessage = response.message || 'Erreur lors de l\'ajout du test';
        }
      },
      error: (error) => {
        console.error('Erreur HTTP:', error);
        this.errorMessage = error.error?.message || 
                         'Une erreur est survenue lors de la communication avec le serveur';
        this.isSubmitting = false;
      },
      complete: () => {
        this.isSubmitting = false;
      }
    });
  }
}