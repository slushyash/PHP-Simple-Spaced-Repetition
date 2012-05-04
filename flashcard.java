public class Flashcard extends Model {

	public String status; // pending, active, new
	public boolean isDue; // due or not
	public String front;
	public String back;
	public int interval;
	public String lastAnswer; // strong, correct, wrong, null if pending
	
	public Flashcard(String front, String back) {
		this.status = "pending";
		this.isDue = false;
		this.front = front;
		this.back = back;
		this.interval = 0;
		this.lastAnswer = null;
	}
	
	public void registerFlashcard() {
		if(status.equals("pending")) {
			this.isDue = false;
			this.status = (this.lastAnswer == "strong") ? "active" : "new";
			this.interval = /* totally forgot TODO */;
		}
		
		else if(status.equals("active")) {
			
		}
		this.save();
	}
	

}
