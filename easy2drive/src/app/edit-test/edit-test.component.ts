import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { StudentService } from '../student.service';

@Component({
  selector: 'app-edit-test',
  templateUrl: './edit-test.component.html',
  styleUrls: ['./edit-test.component.css'],
  standalone: false
})
export class EditTestComponent implements OnInit {
  test = { 
    id_test: 0, 
    theme: '', 
    date: '', 
    score: 0, 
    id_admin: 1, 
    id_eleve: 1 
  };

  constructor(
    private route: ActivatedRoute,
    private studentService: StudentService,
    private router: Router
  ) {}

  ngOnInit(): void {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.studentService.getTests().subscribe({
      next: (response) => {
        if (response.success && response.tests) {
          const foundTest = response.tests.find(t => t.id_test === id);
          if (foundTest) {
            this.test = foundTest;
          }
        }
      },
      error: (error) => {
        console.error('Erreur lors du chargement', error);
      }
    });
  }

  submitForm(): void {
    this.studentService.updateTest(this.test).subscribe({
      next: () => {
        this.router.navigate(['/tests']);
      },
      error: (error) => {
        console.error("Erreur lors de la modification", error);
        alert("Erreur lors de la modification du test");
      }
    });
  }
}