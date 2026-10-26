# AI Model Training Note

This version uses a transparent weighted scoring model implemented in PHP. It behaves like a rule-based prediction model and is easy to defend because the weights are visible.

## Possible Offline ML Workflow

1. Collect 500 to 2,000 historical student records.
2. Clean missing scores and convert categories into numeric values.
3. Train a Decision Tree or Random Forest offline.
4. Evaluate with accuracy, precision, recall, F1-score, and confusion matrix.
5. Export the model as JSON or PHP arrays.
6. Load the exported model in PHP for inference inside XAMPP.

## Example Evaluation Metrics

| Metric | Example |
| --- | --- |
| Accuracy | 87% |
| Precision | 85% |
| Recall | 84% |
| F1-score | 84.5% |

The current PHP engine can later be replaced by an exported tree without changing the dashboard or reporting modules.
