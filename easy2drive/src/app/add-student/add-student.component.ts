import { Component } from '@angular/core';
import { StudentService } from '../student.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-add-student',
  templateUrl: './add-student.component.html',
  styleUrls: ['./add-student.component.css'],
  standalone: false
})
export class AddStudentComponent {
  student = {
    nom: '',
    prenom: '',
    date_naissance: '',
    rue: '',
    code_postal: '',
    ville: '',
    NPEH: '',
    id_auto_ecole: 0 // Changé de string à number
  };

  constructor(
    private studentService: StudentService,
    private router: Router
  ) {}

  addStudent(): void {
    // Conversion des types si nécessaire
    const studentToSend = {
      ...this.student,
      id_auto_ecole: Number(this.student.id_auto_ecole)
    };

    this.studentService.addStudent(studentToSend).subscribe({
      next: () => {
        this.router.navigate(['/students']);
      },
      error: (error) => {
        console.error("Erreur lors de l'ajout", error);
        alert("Erreur lors de l'ajout de l'élève");
      }
    });
  }
}