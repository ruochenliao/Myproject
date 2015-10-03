package com.example.john.myfirstapp;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.AsyncTask;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.ImageView;
import android.widget.TextView;

import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.InputStream;


public class DisplayMessageActivity extends ActionBarActivity {
    public class DownloadImageTask extends AsyncTask<String, Void, Bitmap> {
        ImageView bmImage;

        public DownloadImageTask(ImageView bmImage) {
            this.bmImage = bmImage;
        }

        protected Bitmap doInBackground(String... urls) {
            String urldisplay = urls[0];
            Bitmap mIcon11 = null;
            try {
                InputStream in = new java.net.URL(urldisplay).openStream();
                mIcon11 = BitmapFactory.decodeStream(in);
            } catch (Exception e) {
                Log.e("Error", e.getMessage());
                e.printStackTrace();
            }
            return mIcon11;
        }

        protected void onPostExecute(Bitmap result) {
            bmImage.setImageBitmap(result);
        }
    }

    Bundle bundle1;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_display_message);
        Intent intent =this.getIntent();
        bundle1 = intent.getExtras();
        String json_string = bundle1.getString("json");
        //String jsonMessage = "{\"a\":\"88\",\"b\":\"78\",\"c\":\"99\"}";
        String img_url = null;
        try {
            JSONObject json_obj = new JSONObject(json_string);
            JSONObject item0 = json_obj.getJSONObject("item0");
            JSONObject BI = item0.getJSONObject("basicInfo");
            img_url = BI.getString("title");
        }
        catch(Exception e){
        }
        TextView test_view = (TextView) findViewById(R.id.view_test);
        test_view.setText(img_url);

        //JSONArray json_arry = new JSONArray(json);
        //JSONObject json_obj = new JSONObject();
        //String img_url = json.;
        String keyword = "Result for '"+bundle1.getString("keyword") +"'";
        // show keyword
        TextView show_keyword = (TextView) findViewById(R.id.view_keyword);
        show_keyword.setText(keyword);
        //
        //TextView show_json = (TextView) findViewById(R.id.view_json);
        //show_json.setText(json);
        //TextView test_view = (TextView) findViewById(R.id.view_test);
        //test_view.setText(json_string);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
