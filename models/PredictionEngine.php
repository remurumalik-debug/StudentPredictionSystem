<?php
declare(strict_types=1);

class PredictionEngine
{
    private array $weights = [
        'continuous_assessment' => 0.24,
        'attendance' => 0.18,
        'test_score' => 0.17,
        'previous_cgpa' => 0.16,
        'assignment_score' => 0.10,
        'study_hours' => 0.08,
        'constraint_flag' => 0.07,
    ];

    public function predict(array $input): array
    {
        $normalized = [
            'continuous_assessment' => $this->percent($input['continuous_assessment'] ?? 0),
            'attendance' => $this->percent($input['attendance'] ?? 0),
            'test_score' => $this->percent($input['test_score'] ?? 0),
            'previous_cgpa' => $this->percent(($input['previous_cgpa'] ?? 0) / 5 * 100),
            'assignment_score' => $this->percent($input['assignment_score'] ?? 0),
            'study_hours' => $this->percent(($input['study_hours'] ?? 0) / 40 * 100),
            'constraint_flag' => $this->constraintFlag($input),
        ];

        $score = 0.0;
        foreach ($this->weights as $key => $weight) {
            $score += $normalized[$key] * $weight;
        }
        $score = max(0, min(100, $score));

        $predictedGpa = round($score / 20, 2);
        $grade = $this->gradeClass($predictedGpa, $score);
        $risk = $this->riskLevel($score);
        $confidence = (int)round(min(98, max(55, 62 + abs($score - 50) * .72)));

        return [
            'score' => round($score, 2),
            'predicted_gpa' => $predictedGpa,
            'predicted_grade' => $grade,
            'risk_level' => $risk,
            'confidence' => $confidence,
            'recommendations' => $this->recommendations($normalized, $input, $risk),
        ];
    }

    private function percent(float|int|string $value): float
    {
        return max(0, min(100, (float)$value));
    }

    private function constraintFlag(array $input): float
    {
        $flag = 100;
        if (($input['health_status'] ?? 'Good') === 'Fair') $flag -= 30;
        if (($input['health_status'] ?? 'Good') === 'Poor') $flag -= 60;
        if (($input['financial_challenges'] ?? 'No') === 'Yes') $flag -= 40;
        return $this->percent($flag);
    }

    private function gradeClass(float $gpa, float $score): string
    {
        if ($score < 35) return 'At Risk';
        if ($gpa >= 4.5) return 'First Class';
        if ($gpa >= 3.5) return 'Second Class Upper';
        if ($gpa >= 2.4) return 'Second Class Lower';
        if ($gpa >= 1.5) return 'Third Class';
        if ($gpa >= 1.0) return 'Pass';
        return 'Fail';
    }

    private function riskLevel(float $score): string
    {
        if ($score >= 70) return 'Low';
        if ($score >= 50) return 'Medium';
        return 'High';
    }

    private function recommendations(array $n, array $input, string $risk): array
    {
        $tips = [];
        if ($n['attendance'] < 75) $tips[] = 'Increase class attendance to at least 80%.';
        if ($n['continuous_assessment'] < 65) $tips[] = 'Improve continuous assessment performance through regular practicals and class exercises.';
        if ($n['assignment_score'] < 65) $tips[] = 'Submit assignments early and review lecturer feedback.';
        if ($n['test_score'] < 60) $tips[] = 'Practice past questions weekly before tests.';
        if ($n['study_hours'] < 50) $tips[] = 'Create a study timetable with at least 20 focused hours per week.';
        if (($input['financial_challenges'] ?? 'No') === 'Yes') $tips[] = 'Meet the level adviser for bursary, scholarship, or payment guidance.';
        if (($input['health_status'] ?? 'Good') !== 'Good') $tips[] = 'Use the university health center and inform the department adviser early.';
        if ($risk === 'Low') $tips[] = 'Maintain current learning habits and mentor lower-performing classmates.';
        return array_slice(array_unique($tips), 0, 5);
    }
}
