package com.example.shalini.stockapp;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Editable;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.ListView;

import com.google.android.gms.appindexing.Action;
import com.google.android.gms.appindexing.AppIndex;
import com.google.android.gms.common.api.GoogleApiClient;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import LibraryFiles.JSONParser;

public class HomePage extends AppCompatActivity {
    /**/private List<LocalStorage> lstStrg = new ArrayList<LocalStorage>();
    List<String> responseList;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home_page);
        new JSONParse().execute();
        populateListView();
    }
    public void bindAutoCompView()
    {
       final AutoCompleteTextView objAutoSymbols = (AutoCompleteTextView) findViewById(R.id.autoTxtVwStockSymbol);
        ArrayAdapter<String> adapter = new ArrayAdapter<String>(this,
                android.R.layout.select_dialog_item, responseList);
        objAutoSymbols.setThreshold(1);
        objAutoSymbols.setAdapter(adapter);
        objAutoSymbols.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick (AdapterView<?> parent, View view, int position, long id) {
                String selectedText = (String)parent.getItemAtPosition(position);
                selectedText = selectedText.substring(0,selectedText.indexOf("-"));
                objAutoSymbols.setText(selectedText);

            }
        });



    }
    public void handleButtonClick(View view) {
        Button button = (Button) findViewById(R.id.btnGetQuote);
        startActivity(new Intent(this, StockDetails.class));
    }


    /* public class UserItemAdapter extends ArrayAdapter<LocalStorage> {
         private ArrayList<LocalStorage> lstStorage;

         public UserItemAdapter(Context context, int textViewResourceId, ArrayList<LocalStorage> storage) {
             super(context, textViewResourceId, storage);
             this.lstStorage = storage;
         }
     }*/
    private void populateListView() {
        String[] items = {"red", "blue", "green"};
        ListView listView = (ListView) findViewById(R.id.listView);
        listView.setAdapter(new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, items));
      /*LocalStorage objStorage = new LocalStorage("yolanda","fdsfsfs");
        lstStrg.add(0,objStorage);*/
    }

    private class JSONParse extends AsyncTask<String, String, JSONObject> {

        public JSONParse() {
            try {
                Class.forName("android.os.AsyncTask");
            } catch (ClassNotFoundException e) {
                e.printStackTrace();
            }

        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
           /* //     pDialog.setMessage("Preparing to obtain data...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(false);
            pDialog.show();*/
        }

        @Override
        protected JSONObject doInBackground(String... args) {

            String url = "http://stockapplication-env.us-west-2.elasticbeanstalk.com/";
            JSONParser objParser = new JSONParser();
            JSONObject json = null;
            try {

                HashMap<String, String> params = new HashMap<>();
                params.put("compName", "AAPL");

                json = objParser.makeHttpRequest(
                        url, "GET", params);

                if (json != null) {
                    Log.d("JSON result", json.toString());
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        return json;
        }

        @Override
        protected void onPostExecute(JSONObject json) {

            responseList = new ArrayList<String>();
            try {
                JSONArray objArray = json.getJSONArray("total");
                for(int i=0;i<objArray.length();i++)
                {
                    String s = objArray.getString(i);
                   responseList.add(s);

                }
                bindAutoCompView();

            } catch (JSONException e) {
                e.printStackTrace();
            }


            // Getting JSON Array\
            /*String jsonString = json.toString();
            JSONArray jsonArr = null;
            try {
                jsonArr = new JSONArray(jsonString);
            } catch (JSONException e) {
                e.printStackTrace();
            }


            for (int i = 0; i < jsonArr.length(); i++) {
                JSONObject objJson = null;
                try {
                    e = jsonArr.getJSONObject(i);
                } catch (JSONException e1) {
                    e1.printStackTrace();
                }
                String name = null;
                try {
                    name = e.getString("total");
                } catch (JSONException e1) {
                    e1.printStackTrace();
                }
                responseList.add(name);
            }*/
            // Storing  JSON item in a Variable


        }


    }
}
