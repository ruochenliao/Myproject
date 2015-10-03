package com.example.john.myfirstapp;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
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
            Log.i("debug","display image");
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
        String img_url = null;
        final String[] item_url = new String[5];
        int cycle_num;
        String[] item = new String[5];
        String[] title = new String[5];
        //String item[5];
        //int i=0;
        try {
            JSONObject json_obj = new JSONObject(json_string);
            String result_count = json_obj.getString("resultCount");
            if( Integer.parseInt( result_count ) >5){
                cycle_num = 5;
            }
            else{
                cycle_num = Integer.parseInt( result_count );
            }
            for(int i =0; i<cycle_num;i++){
                item[i] = "item"+i;
                JSONObject item_i = json_obj.getJSONObject(item[i]);
                JSONObject BI = item_i.getJSONObject("basicInfo");
                item_url[i] = BI.getString("viewItemURL");
                title[i] = BI.getString("title");
                img_url = BI.getString("pictureURLSuperSize");
                if(img_url.equals("") ){
                    img_url = BI.getString("galleryURL");
                }
            }
        }
        catch(Exception e){}
        TextView title0 = (TextView) findViewById(R.id.title0);
        title0.setText(title[0]);
        ImageView image_view0 = (ImageView) findViewById(R.id.image0 );
        image_view0.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse( item_url[0] ));
                startActivity(browserIntent);
            }
        });
        DownloadImageTask download_img = new DownloadImageTask(image_view0);
        download_img.execute(img_url);
        //TextView test_view = (TextView) findViewById(R.id.view_test);
        //test_view.setText(img_url);
//        ImageView image_view = (ImageView) findViewById(R.id.image1);
//        DownloadImageTask download_img = new DownloadImageTask(image_view);
//        download_img.execute(img_url);

        String keyword = "Result for '"+bundle1.getString("keyword") +"'";
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
